<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Http\Request;

class HabitController extends Controller
{
    public function index()
    {
        // Ambil semua data dari database
        $habits = Habit::all();

        // Tampilkan langsung datanya (JSON) untuk ngetes
        return $habits;
    }
}
