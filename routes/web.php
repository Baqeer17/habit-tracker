<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController; 

use App\Http\Controllers\LandingController;

// Halaman Utama (Landing Page)
Route::get('/', [LandingController::class, 'index'])->middleware('guest')->name('landing');

// Halaman Login (Kasih nama 'login' biar Laravel tau ini halaman login utama)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);

// Cari baris dashboard yang lama, GANTI dengan ini:
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// --- SALAT WAJIB TRACKING ---
use App\Http\Controllers\PrayerController;
Route::middleware('auth')->group(function () {
    Route::get('/salat-wajib', [PrayerController::class, 'index'])->name('prayers.index');
    Route::post('/salat-wajib/toggle', [PrayerController::class, 'toggle'])->name('prayers.toggle');
    Route::get('/tracker', [\App\Http\Controllers\TrackerController::class, 'index'])->name('tracker.index');
    Route::get('/dzikir', [\App\Http\Controllers\DzikirController::class, 'index'])->name('dzikir.index');
    Route::post('/dzikir/log', [\App\Http\Controllers\DzikirController::class, 'logProgress'])->name('dzikir.log');
    Route::get('/quran', [App\Http\Controllers\QuranController::class, 'index'])->name('quran.index');
    Route::get('/quran/{nomor}', [App\Http\Controllers\QuranController::class, 'show'])->name('quran.show');
    Route::get('/quran/juz/{nomor}', [App\Http\Controllers\QuranController::class, 'juz'])->name('quran.juz');
    
    // Jurnal Tilawah
    Route::post('/quran-log', [App\Http\Controllers\QuranLogController::class, 'store'])->name('quran-log.store');
    Route::get('/quran-log/weekly', [App\Http\Controllers\QuranLogController::class, 'weeklyProgress'])->name('quran-log.weekly');

    // Zakat
    Route::get('/zakat', [App\Http\Controllers\ZakatController::class, 'index'])->name('zakat.index');
    Route::post('/zakat', [App\Http\Controllers\ZakatController::class, 'store'])->name('zakat.store');

    // Settings — core
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/push-subscription', [\App\Http\Controllers\SettingsController::class, 'savePushSubscription'])->name('settings.push.subscription');

    // Settings — change password
    Route::post('/settings/change-password', [\App\Http\Controllers\SettingsController::class, 'changePassword'])->name('settings.change-password');

    // Settings — export CSV
    Route::get('/settings/export-csv', [\App\Http\Controllers\SettingsController::class, 'exportCsv'])->name('settings.export-csv');

    // Settings — delete account
    Route::delete('/settings/delete-account', [\App\Http\Controllers\SettingsController::class, 'deleteAccount'])->name('settings.delete-account');

    // Settings — report (PDF print)
    Route::get('/settings/report', [\App\Http\Controllers\SettingsController::class, 'generateReport'])->name('settings.report');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});


// --- RUTE GOOGLE (Cukup satu pasang saja) ---

// 1. Lempar ke Google
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');

// 2. Balik dari Google
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::get('/shalat-sunnah', function () {
    return view('shalat-sunnah.shalat-sunnah');
})->name('shalat-sunnah.halaman-sunnah');

Route::get('/shalat-sunnah/rawatib', [PrayerController::class, 'rawatib'])->name('shalat-sunnah.rawatib');

Route::get('/shalat-sunnah/ghairu-muakkad', [PrayerController::class, 'ghairuMuakkad'])->name('shalat-sunnah.ghairu-muakkad');

// One Day One Hadis
use App\Http\Controllers\HadithController;
Route::get('/one-day-one-hadith', [HadithController::class, 'index'])->name('hadith.index');