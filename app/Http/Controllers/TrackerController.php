<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrayerLog;
use App\Models\QuranLog;
use App\Models\DzikirLog;
use Carbon\Carbon;

class TrackerController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $period = $request->query('period', 'weekly');

        // 1. Categories Report based on Timeframe
        $endOfPeriod = Carbon::now()->endOfDay();
        $startOfPeriod = Carbon::now()->startOfDay();
        $totalDays = 7;

        switch ($period) {
            case 'monthly':
                $startOfPeriod = Carbon::now()->subMonths(1)->startOfDay();
                break;
            case '3_months':
                $startOfPeriod = Carbon::now()->subMonths(3)->startOfDay();
                break;
            case '6_months':
                $startOfPeriod = Carbon::now()->subMonths(6)->startOfDay();
                break;
            case 'yearly':
                $startOfPeriod = Carbon::now()->subYears(1)->startOfDay();
                break;
            case '3_years':
                $startOfPeriod = Carbon::now()->subYears(3)->startOfDay();
                break;
            case '5_years':
                $startOfPeriod = Carbon::now()->subYears(5)->startOfDay();
                break;
            case 'all_time':
                $startOfPeriod = Carbon::parse($user->created_at)->startOfDay();
                break;
            case 'weekly':
            default:
                $startOfPeriod = Carbon::now()->subDays(6)->startOfDay();
                break;
        }

        // Batasi rentang pencarian TERTUA adalah sejak akun dibuat 
        // (Supaya hari sebelum download aplikasi tidak terhitung sebagai 'bolong' ibadah)
        $accountCreated = Carbon::parse($user->created_at)->startOfDay();
        if ($startOfPeriod->lt($accountCreated)) {
            $startOfPeriod = clone $accountCreated;
        }

        $totalDays = max(1, (int) $startOfPeriod->diffInDays($endOfPeriod) + 1);

        // A. Shalat Progress
        $prayerLogsPeriod = PrayerLog::where('user_id', $user->id)
            ->whereBetween('date', [$startOfPeriod, $endOfPeriod])
            ->get();
            
        $shalatCompleted = 0;
        foreach ($prayerLogsPeriod as $log) {
            $shalatCompleted += $log->fajr ? 1 : 0;
            $shalatCompleted += $log->dhuhr ? 1 : 0;
            $shalatCompleted += $log->asr ? 1 : 0;
            $shalatCompleted += $log->maghrib ? 1 : 0;
            $shalatCompleted += $log->isha ? 1 : 0;
        }
        $expectedShalat = $totalDays * 5;
        $shalatPercentage = round(($shalatCompleted / $expectedShalat) * 100);

        // B. Tilawah Progress (Active days)
        $quranLogsPeriod = QuranLog::where('user_id', $user->id)
            ->whereBetween('date', [$startOfPeriod, $endOfPeriod])
            ->select('date')
            ->distinct()
            ->get();
        $quranPercentage = round(($quranLogsPeriod->count() / $totalDays) * 100);

        // C. Dzikir Progress (Pagi & Petang)
        $dzikirLogsPeriod = DzikirLog::where('user_id', $user->id)
            ->whereBetween('date', [$startOfPeriod, $endOfPeriod])
            ->get();
            
        $dzikirCompleted = 0;
        foreach ($dzikirLogsPeriod as $log) {
            $dzikirCompleted += $log->pagi_completed ? 1 : 0;
            $dzikirCompleted += $log->petang_completed ? 1 : 0;
        }
        $expectedDzikir = $totalDays * 2; // Pagi + Petang expected per day
        $dzikirPercentage = round(($dzikirCompleted / $expectedDzikir) * 100);

        $weeklyCategories = collect([
            ['name' => 'Shalat', 'icon' => 'fa-hands-praying', 'colorCode' => '#0d9488', 'colorClass' => 'teal-600', 'bgClass' => 'teal-50', 'percentage' => $shalatPercentage],
            ['name' => 'Tilawah', 'icon' => 'fa-book-open', 'colorCode' => '#059669', 'colorClass' => 'emerald-600', 'bgClass' => 'emerald-50', 'percentage' => $quranPercentage],
            ['name' => 'Dzikir', 'icon' => 'fa-mosque', 'colorCode' => '#d97706', 'colorClass' => 'amber-600', 'bgClass' => 'amber-50', 'percentage' => $dzikirPercentage],
        ]);

        // 2. Today's Sunnah Status (Detailed Progress Checklists)
        $todayStr = Carbon::now()->format('Y-m-d');
        $todayLog = PrayerLog::where('user_id', $user->id)->where('date', $todayStr)->first();

        $sunnahStatus = [
            'dhuha' => $todayLog ? $todayLog->dhuha : false,
            'tahajud' => $todayLog ? $todayLog->tahajud : false,
            'witir' => $todayLog ? $todayLog->witir : false,
        ];

        // 3. Daily Quote (Random)
        $islamicQuotes = [
            "Amalan yang paling dicintai Allah adalah yang kontinu meski sedikit. (HR. Muslim)",
            "Jadikan shalat sebagai tempat istirahatmu, bukan bebanmu.",
            "Dzikir pagi dan petang adalah perisai pelindung mukmin sejati.",
            "Hati yang terpaut pada masjid akan mendapatkan naungan-Nya kelak.",
            "Sabar dalam ketaatan memang berat, namun buahnya sangat manis."
        ];
        $dailyQuote = $islamicQuotes[array_rand($islamicQuotes)];

        // 4. Multi-Category Line Chart Data (Max 31 days to avoid freezing)
        $chartStart = clone $startOfPeriod;
        if ($chartStart->diffInDays($endOfPeriod) > 31) {
            $chartStart = (clone $endOfPeriod)->subDays(30)->startOfDay();
        }
        // Ensure chartStart never exceeds account creation date
        if ($chartStart->lt($accountCreated)) {
            $chartStart = clone $accountCreated;
        }
        $chartDaysCount = max(1, (int) $chartStart->diffInDays($endOfPeriod) + 1);

        $prayerLogsChart = PrayerLog::where('user_id', $user->id)
            ->whereBetween('date', [$chartStart->format('Y-m-d'), $endOfPeriod->format('Y-m-d')])
            ->get()->keyBy(function($i) { return $i->date->format('Y-m-d'); });
            
        $quranLogsChart = QuranLog::where('user_id', $user->id)
            ->whereBetween('date', [$chartStart->format('Y-m-d'), $endOfPeriod->format('Y-m-d')])
            ->select('date')->distinct()->get()->keyBy(function($i) { 
                // date from QuranLog may be a string or Carbon. Handle both.
                return is_string($i->date) ? substr($i->date, 0, 10) : $i->date->format('Y-m-d'); 
            });
            
        $dzikirLogsChart = DzikirLog::where('user_id', $user->id)
            ->whereBetween('date', [$chartStart->format('Y-m-d'), $endOfPeriod->format('Y-m-d')])
            ->get()->keyBy(function($i) { 
                return is_string($i->date) ? substr($i->date, 0, 10) : $i->date->format('Y-m-d'); 
            });

        $chartLabels = [];
        $dataShalatWajib = [];
        $dataShalatSunnah = [];
        $dataShalatSemua = [];
        $dataTilawah = [];
        $dataDzikir = [];

        for ($i = 0; $i < $chartDaysCount; $i++) {
            $dateObj = (clone $chartStart)->addDays($i);
            $dateKey = $dateObj->format('Y-m-d');
            $chartLabels[] = $dateObj->format('d M');

            // Shalat
            $pLog = $prayerLogsChart->get($dateKey);
            $cWajib = 0; $cSunnah = 0;
            if ($pLog) {
                $cWajib += $pLog->fajr ? 1 : 0;
                $cWajib += $pLog->dhuhr ? 1 : 0;
                $cWajib += $pLog->asr ? 1 : 0;
                $cWajib += $pLog->maghrib ? 1 : 0;
                $cWajib += $pLog->isha ? 1 : 0;
                
                $cSunnah += $pLog->dhuha ? 1 : 0;
                $cSunnah += $pLog->tahajud ? 1 : 0;
                $cSunnah += $pLog->witir ? 1 : 0;
            }
            $dataShalatWajib[] = round(($cWajib / 5) * 100);
            $dataShalatSunnah[] = round(($cSunnah / 3) * 100);
            $dataShalatSemua[] = round((($cWajib + $cSunnah) / 8) * 100);

            // Tilawah
            $qLog = $quranLogsChart->get($dateKey);
            $dataTilawah[] = $qLog ? 100 : 0;

            // Dzikir
            $dzLog = $dzikirLogsChart->get($dateKey);
            $cDzikir = 0;
            if ($dzLog) {
                $cDzikir += $dzLog->pagi_completed ? 1 : 0;
                $cDzikir += $dzLog->petang_completed ? 1 : 0;
            }
            $dataDzikir[] = round(($cDzikir / 2) * 100);
        }

        $chartData = [
            'labels' => $chartLabels,
            'shalatWajib' => $dataShalatWajib,
            'shalatSunnah' => $dataShalatSunnah,
            'shalatSemua' => $dataShalatSemua,
            'tilawah' => $dataTilawah,
            'dzikir' => $dataDzikir,
        ];

        return view('tracker', compact(
            'weeklyCategories', 
            'sunnahStatus', 
            'dailyQuote',
            'chartData',
            'user',
            'period'
        ));
    }
}
