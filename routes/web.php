<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HabitController;

Route::get('/', function () {
    return redirect('/habits');
});

Route::get('/habits', [HabitController::class, 'index']);
