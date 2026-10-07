<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HabitController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| 1. Rute Publik (Bisa Diakses Tanpa Login)
|--------------------------------------------------------------------------
*/

// Auth Routes (Rate Limited to prevent Brute-Force)
Route::middleware('throttle:5,1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Utility Routes (Jadwal Shalat)
Route::get('/prayer-times', function (Request $request) {
    $lat = $request->query('latitude');
    $lng = $request->query('longitude');

    // Default Jakarta jika tidak ada input
    if (!$lat || !$lng) {
        $lat = -6.2088;
        $lng = 106.8456;
    }

    $response = Http::get("http://api.aladhan.com/v1/timings/" . time(), [
        'latitude'  => $lat,
        'longitude' => $lng,
        'method'    => 20, // Kemenag RI
    ]);
    
    $data = $response->json();
    if (isset($data['data']['timings']['Sunrise'])) {
        $sunrise = $data['data']['timings']['Sunrise'];
        $dhuha = date('H:i', strtotime($sunrise . ' +20 minutes'));
        $data['data']['timings']['Dhuha'] = $dhuha;
    }

    return $data;
});

/*
|--------------------------------------------------------------------------
| 2. Rute Private (Harus Pakai Token / Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    
    // Cek Data Diri User (Ini yang tadi Error 404)
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Update Lokasi User (Sekarang otomatis mendeteksi user dari Token)
    Route::patch('/user/location', function (Request $request) {
        $validated = $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Mengambil user yang sedang login (bukan lagi hardcoded ID 2)
        $user = $request->user(); 

        $user->update($validated);

        return response()->json([
            'message' => 'Location updated successfully',
            'data' => [
                'name' => $user->name,
                'latitude'  => $user->latitude,
                'longitude' => $user->longitude,
            ]
        ]);
    });

    // Habit Routes (Agar hanya user yang login bisa akses)
    Route::get('/habits', [HabitController::class, 'index']);
    Route::post('/habits', [HabitController::class, 'store']);
    Route::delete('/habits/{id}', [HabitController::class, 'destroy']);
    Route::patch('/habits/{id}/toggle', [HabitController::class, 'toggle'])->middleware('throttle:30,1');
});