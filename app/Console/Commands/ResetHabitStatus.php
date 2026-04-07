<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetHabitStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'habits:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    \App\Models\Habit::query()->update(['status' => 'pending']);
    $this->info('Semua status habit berhasil di-reset!');
    }
}
