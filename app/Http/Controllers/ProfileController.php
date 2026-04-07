<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // 1. Calculate Streak
        $streak = $this->calculateStreak($user->id);
        
        // 2. Badge Logic
        $badges = $this->getBadges($user->id);
        
        return view('profile', [
            'user' => $user,
            'streak' => $streak,
            'badges' => $badges,
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    private function calculateStreak($userId)
    {
        $streak = 0;
        $date = \Carbon\Carbon::today();
        
        // Check today first
        if ($this->hasActivity($userId, $date)) {
            $streak++;
            $date->subDay();
        } else {
            // If nothing today, start from yesterday
            $date->subDay();
        }

        // Count backwards
        while ($this->hasActivity($userId, $date)) {
            $streak++;
            $date->subDay();
            
            // Safety break to prevent infinite loop
            if ($streak > 1000) break;
        }

        return $streak;
    }

    private function hasActivity($userId, $date)
    {
        $dateStr = $date->format('Y-m-d');
        
        $hasPrayer = \App\Models\PrayerLog::where('user_id', $userId)->where('date', $dateStr)->exists();
        $hasQuran = \App\Models\QuranLog::where('user_id', $userId)->where('date', $dateStr)->exists();
        $hasDzikir = \App\Models\DzikirLog::where('user_id', $userId)->where('date', $dateStr)->exists();

        return $hasPrayer || $hasQuran || $hasDzikir;
    }

    private function getBadges($userId)
    {
        $prayerCount = \App\Models\PrayerLog::where('user_id', $userId)->count();
        $quranCount = \App\Models\QuranLog::where('user_id', $userId)->count();
        $dzikirCount = \App\Models\DzikirLog::where('user_id', $userId)->count();
        $streak = $this->calculateStreak($userId);

        // Predefined Badges (Logic can be refined)
        return [
            ['name' => 'First Step', 'icon' => 'fa-shoe-prints', 'unlocked' => $prayerCount > 0],
            ['name' => '7 Days Streak', 'icon' => 'fa-calendar-week', 'unlocked' => $streak >= 7],
            ['name' => '30 Days Warrior', 'icon' => 'fa-shield-halved', 'unlocked' => $streak >= 30],
            ['name' => 'Quran Student', 'icon' => 'fa-book-quran', 'unlocked' => $quranCount > 0],
            ['name' => 'Dzikir Practitioner', 'icon' => 'fa-mosque', 'unlocked' => $dzikirCount > 0],
            ['name' => 'Perfect Day', 'icon' => 'fa-star', 'unlocked' => $prayerCount > 10], // Simple mock logic
            ['name' => 'Sunnah Lover', 'icon' => 'fa-moon', 'unlocked' => false], // Mock
            ['name' => 'Century Club', 'icon' => 'fa-trophy', 'unlocked' => $prayerCount >= 100],
            ['name' => 'Elite Routine', 'icon' => 'fa-crown', 'unlocked' => $streak >= 100],
        ];
    }
}
