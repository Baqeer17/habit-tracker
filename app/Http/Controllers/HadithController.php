<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\HadithSearchIndex;

use App\Models\UserInterest;
use Illuminate\Support\Facades\Session;

class HadithController extends Controller
{
    private function trackInterest($topic, $score)
    {
        if (!$topic) return;
        
        $sessionId = Session::getId();
        $userId = auth()->id();
        $normalizedTopic = strtolower($topic);

        // Simple upsert logic
        $interest = UserInterest::where('session_id', $sessionId)
            ->where('topic', $normalizedTopic)
            ->first();

        if ($interest) {
            $interest->increment('score', $score);
        } else {
            UserInterest::create([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'topic' => $normalizedTopic,
                'score' => $score
            ]);
        }
    }

    public function index(Request $request)
    {
        $keyword = $request->query('topic');
        $narrator = $request->query('narrator');
        $number = $request->query('number');

        // Case 1: Specific Hadith Request (Search or Click from Library)
        if ($narrator && $number) {
            $hadith = $this->fetchSpecificHadith($narrator, $number, $request->query('source'));
            if (!$hadith) {
                session()->flash('warning', 'Hadits spesifik tidak ditemukan. Menampilkan hadits harian.');
                // Fallback to daily
                $selection = $this->getDailySelection();
                $hadith = $selection['main'];
            } else {
                $hadith->source = "Pustaka: " . ucfirst($narrator);
            }
            // For the library, we still want the daily selection even if viewing a specific hadith
            $selection = $this->getDailySelection();
            $library = $selection['library'];
            
        } elseif ($keyword) {
            // Case 2: Local Database Search
            $searchResults = HadithSearchIndex::whereRaw(
                "search_vector @@ plainto_tsquery('indonesian', ?)",
                [$keyword]
            )
            ->orderByRaw("ts_rank(search_vector, plainto_tsquery('indonesian', ?)) DESC", [$keyword])
            ->paginate(15);
            
            if ($searchResults->isNotEmpty()) {
                // TRACKING: Search Interest (+2) ONLY if results found
                $this->trackInterest($keyword, 2);
            }

            $hadith = null; // Hide main hadith card
            $library = null; // Hide random library
            
            // TYPO DETECTION
            $typoSuggestion = null;
            if ($searchResults->isEmpty()) {
                $typoSuggestion = $this->findClosestKeyword($keyword);
            }
            
        } else {
            // Case 3: Default Daily View
            $selection = $this->getDailySelection();
            $hadith = $selection['main'];
            $library = $selection['library'];
        }

        // Add random tags fallback
        if ($hadith && !isset($hadith->tags)) {
            $hadith->tags = $this->generateRandomTags();
        }

        // FETCH RECOMMENDATIONS
        $recommendations = $this->getRecommendations();

        return view('hadith.index', [
            'hadith' => $hadith,
            'library' => $library,
            'currentTopic' => $keyword,
            'searchResults' => $searchResults ?? null,
            'is_history' => $selection['is_history'] ?? false,
            'recommendations' => $recommendations,
            // Ensure we don't error if session is fresh
            'userInterests' => UserInterest::where('session_id', Session::getId())->orderBy('score', 'desc')->limit(5)->pluck('topic'),
            'typoSuggestion' => $typoSuggestion ?? null
        ]);
    }




    // New Logic: Get Daily Selection (Main + 6 Library Items) with distinct topics
    private function getDailySelection()
    {
        // 1. Check if user has history
        $history = session()->get('hadith_history', []);
        
        if (!empty($history)) {
            // Convert array to object for view compatibility
            $library = array_map(function($item) {
                return (object) [
                    'narrator' => $item['narrator'],
                    'number' => $item['number'],
                    'arabic' => $item['arabic'] . '...',
                    'translation' => $item['translation'],
                    'source' => $item['source'] ?? 'Terakhir Dibaca'
                ];
            }, $history);

            // We still need a main hadith (random daily) if not explicitly set
            // But usually this function is called when standard view.
            // Let's get the daily main hadith from cache logic below, but override library.
        }

        $today = Carbon::today()->format('Y-m-d');
        $cacheKey = "hadith_daily_selection_{$today}";

        $dailyData = Cache::remember($cacheKey, 60 * 24, function () use ($today) {
            // ... (Same Random Logic as before) ...
            // 1. Seed Random Number Generator with Date
            mt_srand(strtotime($today));

            // 2. Get Topics and Shuffle safely
            $map = $this->getTopicMap();
            $topics = array_keys($map);
            
            // Fisher-Yates shuffle implementation that respects the seeded random
            for ($i = count($topics) - 1; $i > 0; $i--) {
                $j = mt_rand(0, $i);
                $temp = $topics[$i];
                $topics[$i] = $topics[$j];
                $topics[$j] = $temp;
            }

            // 3. Select Topics
            $mainTopic = $topics[0]; // 1st topic for main
            $libraryTopics = array_slice($topics, 1, 6); // Next 6 for library

            // 4. Fetch Main Hadith
            $mainHadith = $this->fetchHadithFromTopic($mainTopic, $map);

            // 5. Fetch Library Hadiths (Random Fallback)
            $randomLibrary = [];
            foreach ($libraryTopics as $libTopic) {
                $item = $this->fetchHadithFromTopic($libTopic, $map);
                if ($item) {
                    $item->source = "Topik: " . ucfirst($libTopic);
                    $randomLibrary[] = $item;
                }
            }

            // Reset random seed
            mt_srand();

            return [
                'main' => $mainHadith,
                'library' => $randomLibrary
            ];
        });

        // Use history if available, otherwise use random library
        return [
            'main' => $dailyData['main'],
            'library' => !empty($history) ? $library : $dailyData['library'],
            'is_history' => !empty($history) // Flag for View
        ];
    }

    private function fetchHadithFromTopic($topic, $map)
    {
        if (!isset($map[$topic])) return null;
        
        $ids = $map[$topic];
        // Deterministic selection based on date could be done here too, 
        // but simple array_rand is fine if the list is cached
        // However, since we are inside cached closure with seeded rand, array_rand is deterministic enough
        $id = $ids[mt_rand(0, count($ids) - 1)];
        
        $hadith = $this->fetchSpecificHadith('bukhari', $id);
        if ($hadith) {
            $hadith->source = "Topik: " . ucfirst($topic);
        }
        return $hadith;
    }

    // Expanded Topic Map
    private function getTopicMap()
    {
        return [
            'sabar'     => [6470, 6114, 5641, 1496],
            'ilmu'      => [59, 60, 61, 73, 88],
            'sedekah'   => [1417, 1426, 1433, 2747],
            'orangtua'  => [5971, 5970, 5625, 5626], 
            'shalat'    => [345, 346, 347, 528, 504],
            'syukur'    => [6472, 2977, 6005],
            'niat'      => [1, 54, 2529],
            'ikhlas'    => [56, 72, 1],
            'doa'       => [5864, 5870, 5875],
            'taubat'    => [5834, 5835],
            'kematian'  => [600, 601, 602], // Dummy/Random IDs related to Jenazah
            'akhlak'    => [5629, 5630, 5631], // Adab
            'iman'      => [8, 9, 10, 11, 12],
            'islam'     => [7, 24, 48]
        ];
    }

    private function getHadithByTopic($topic)
    {
        $map = $this->getTopicMap();
        $normalizedTopic = strtolower($topic);

        if (array_key_exists($normalizedTopic, $map)) {
            $ids = $map[$normalizedTopic];
            $randomId = $ids[array_rand($ids)];
            $hadith = $this->fetchSpecificHadith('bukhari', $randomId);
            
            if ($hadith) {
                // Override source to show it's from a topic
                $hadith->source = "Topik: " . ucfirst($normalizedTopic);
                return $hadith;
            }
        }

        // Fallback if topic not found or API fails for specific ID
        session()->flash('warning', "Topik '{$topic}' tidak ditemukan atau hadits belum tersedia. Menampilkan hadits acak untuk Anda.");
        
        // Return a totally random one if topic search fails
        return $this->fetchRandomHadith();
    }

    private function fetchRandomHadith()
    {
        $random = rand(1, 2000);
        return $this->fetchSpecificHadith('bukhari', $random);
    }
    
    private function fetchSpecificHadith($narrator, $number, $sourceParam = null)
    {
        try {
            $response = Http::timeout(5)->get("https://api.hadith.gading.dev/books/{$narrator}/{$number}");

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['data']['contents'])) {
                    $hadith = (object) [
                        'arabic' => $data['data']['contents']['arab'],
                        'translation' => $data['data']['contents']['id'],
                        'narrator' => ucfirst($narrator),
                        'number' => $data['data']['contents']['number'],
                        'source' => $sourceParam ? "Topik: " . ucfirst($sourceParam) : 'One Day One Hadith',
                        'tags' => $this->generateRandomTags() 
                    ];

                    // Case: Track View Interest (+1)
                    if ($sourceParam) {
                        $this->trackInterest($sourceParam, 1);
                    }

                    // --- HISTORY LOGIC START ---
                    // Store minimal data to session
                    $history = session()->get('hadith_history', []);
                    
                    // Create unique ID for deduplication
                    $uniqueKey = strtolower($narrator) . ':' . $number;
                    
                    // Remove if exists (to move to top)
                    $history = array_filter($history, function($item) use ($uniqueKey) {
                        return ($item['key'] ?? '') !== $uniqueKey;
                    });
                    
                    // Determine Source Label for History
                    $historySource = $sourceParam ? "Topik: " . ucfirst($sourceParam) : 'Hadits Pilihan';

                    // Add to top
                    array_unshift($history, [
                        'key' => $uniqueKey,
                        'narrator' => ucfirst($narrator),
                        'number' => $hadith->number,
                        'arabic' => Str::limit($hadith->arabic, 50), // Short preview
                        'translation' => Str::limit($hadith->translation, 100),
                        'source' => $historySource
                    ]);

                    // Limit to 15 items
                    $history = array_slice($history, 0, 15);
                    
                    session()->put('hadith_history', $history);
                    // --- HISTORY LOGIC END ---

                    return $hadith;
                }
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getRecommendations()
    {
        $sessionId = Session::getId();
        
        // Get top 3 topics by score
        $topInterests = UserInterest::where('session_id', $sessionId)
            ->orderBy('score', 'desc')
            ->limit(3)
            ->pluck('topic')
            ->toArray();

        if (empty($topInterests)) {
            return collect(); // Return empty collection if no interests
        }

        // Build OR query for Full-Text Search
        // We'll search for ANY of the top topics
        $queryParts = [];
        $bindings = [];
        foreach ($topInterests as $interest) {
            $queryParts[] = "plainto_tsquery('indonesian', ?)";
            $bindings[] = $interest;
        }
        
        $tsQuery = implode(" || ", $queryParts); // Combine with OR

        // Fetch recommendations, excluding specific IDs if needed (optional)
        // Ensure we get a good mix
        return HadithSearchIndex::whereRaw(
            "search_vector @@ ($tsQuery)",
            $bindings
        )
        ->inRandomOrder() // Shuffle recommendations from the matched pool
        ->limit(6)
        ->get()
        ->map(function($item) {
             // Add source label
            $item->source = "Rek: Topik Anda";
            return $item;
        });
    }

    private function generateRandomTags()
    {
        $tags = [
            '#sabar', '#ikhlas', '#ilmu', '#sedekah', '#sholat', 
            '#orangtua', '#akhlak', '#syukur', '#tawakal', '#rezeki',
            '#kebaikan', '#iman', '#islam', '#doa', '#taubat'
        ];

        // Pick 3 random tags
        $randomKeys = array_rand($tags, 3);
        return [
            $tags[$randomKeys[0]],
            $tags[$randomKeys[1]],
            $tags[$randomKeys[2]]
        ];
    }

    private function findClosestKeyword($input)
    {
        // 1. Dictionary of Common Islamic Terms
        $dictionary = [
            'sabar', 'shalat', 'zakat', 'puasa', 'haji', 'umrah', 
            'sedekah', 'infak', 'iman', 'islam', 'ihsan', 'taqwa', 
            'ikhlas', 'syukur', 'tawakal', 'rezeki', 'jodoh', 
            'orangtua', 'birrul walidain', 'ilmu', 'adab', 'akhlak',
            'kematian', 'surga', 'neraka', 'dosa', 'pahala', 'tobat',
            'istighfar', 'dzikir', 'doa', 'quran', 'sunnah', 'nabi',
            'rasul', 'kiamat', 'takdir', 'wudhu', 'mandi', 'junub',
            'nikah', 'cerai', 'waris', 'hutang', 'riba', 'halal', 'haram',
            'hijrah', 'jihad', 'sakit', 'sehat', 'tetangga', 'tamu',
            'yatim', 'janda', 'miskin', 'kaya', 'pemimpin', 'amanah',
            'jujur', 'bohong', 'ghibah', 'fitnah', 'marah', 'sombong'
        ];

        // 2. Add high-scoring user interests to dictionary (Dynamic Learning)
        $popularInterests = UserInterest::orderBy('score', 'desc')
            ->limit(50)
            ->pluck('topic')
            ->toArray();
            
        // Remove the current input from dictionary to avoid suggesting itself
        $popularInterests = array_diff($popularInterests, [strtolower($input)]);
        
        $dictionary = array_unique(array_merge($dictionary, $popularInterests));

        $shortestDistance = -1;
        $closestWord = null;

        foreach ($dictionary as $word) {
            $lev = levenshtein(strtolower($input), strtolower($word));

            if ($lev == 0) {
                return $word; // Exact match
            }

            if ($lev <= 2 && ($shortestDistance == -1 || $lev < $shortestDistance)) {
                $closestWord = $word;
                $shortestDistance = $lev;
            }
        }

        return $closestWord;
    }
}
