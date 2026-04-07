<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\HadithSearchIndex;

class ImportHadith extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hadith:import {narrator : The name of the narrator (e.g., bukhari, muslim, abudaud, tirmidzi, nasai, ibnumajah, ahmad, malik, darimi)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import 7000+ Hadiths from various narrators via api.hadith.gading.dev';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $narrator = strtolower($this->argument('narrator'));
        $validNarrators = ['bukhari', 'muslim', 'abudaud', 'tirmidzi', 'nasai', 'ibnumajah', 'ahmad', 'malik', 'darimi'];

        if (!in_array($narrator, $validNarrators)) {
            $this->error("Invalid narrator! Available options: " . implode(', ', $validNarrators));
            return 1;
        }

        $this->info("Starting Import for Narrator: " . ucfirst($narrator) . " (Target: 7000 items)...");
        
        $total = 7000; // Most books have fewer than 7000, but this covers most.
        $chunkSize = 300;
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        for ($start = 1; $start <= $total; $start += $chunkSize) {
            $end = min($start + $chunkSize - 1, $total);
            
            try {
                // Fetch from API with Dynamic Narrator
                $response = Http::timeout(30)->get("https://api.hadith.gading.dev/books/{$narrator}?range={$start}-{$end}");
                
                if ($response->successful()) {
                    $data = $response->json();
                    $hadiths = $data['data']['hadiths'] ?? [];

                    // If empty, it means we reached the end of the book
                    if (empty($hadiths)) {
                        $this->info("\nReached end of book at number {$start}. Stopping.");
                        break;
                    }

                    foreach ($hadiths as $item) {
                        try {
                            HadithSearchIndex::updateOrCreate(
                                [
                                    'narrator' => $narrator, // Dynamic Narrator
                                    'number' => $item['number']
                                ],
                                [
                                    'content' => $item['id'],
                                    'arabic' => $item['arab']
                                ]
                            );
                        } catch (\Exception $e) {
                            // Ignore duplicates or specific insert errors
                            continue;
                        }
                        $bar->advance();
                    }
                } else {
                    // API Error or End of Book (404/400 usually means end of range)
                    if ($response->status() == 404 || $response->status() == 400) {
                         $this->info("\nRange {$start}-{$end} not found or out of bounds (End of Book).");
                         break;
                    }
                    $this->error("\nFailed to fetch range {$start}-{$end}. Status: " . $response->status());
                }

            } catch (\Exception $e) {
                // Just log error and continue to next chunk (or stop if critical)
                $this->error("\nError at range {$start}-{$end}: " . $e->getMessage());
                // Don't break here, try next chunk just in case it's a temporary glitch, 
                // unless it's a connection issue. For now, we continue.
            }

            // Be nice to the API
            sleep(1);
        }

        $bar->finish();
        $this->info("\nImport Completed Successfully for " . ucfirst($narrator) . "!");
        return 0;
    }
}
