<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Http\Request;

class HabitController extends Controller
{
    // 1. Tampilkan Habit milik SAYA saja
    public function index(Request $request)
    {
        return $request->user()->habits;
    }

    // 2. Tambah Habit Baru (FIX ERROR 500 DI SINI)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|max:255',
            'description' => 'nullable',
            'icon'        => 'nullable',
            'status'      => 'nullable',
        ]);

        if (empty($validated['status'])) {
            $validated['status'] = 'pending';
        }

        // --- PERHATIKAN BAGIAN INI ---
        // Kita tidak pakai Habit::create() biasa.
        // Kita pakai $request->user()->habits()->create()
        // Ini otomatis mengisi kolom 'user_id' dengan ID kamu.
        $habit = $request->user()->habits()->create($validated);

        return response()->json([
            'message' => 'Habit created successfully',
            'data'    => $habit
        ], 201);
    }

    // 3. Hapus Habit (Milik sendiri)
    public function destroy(Request $request, $id)
    {
        $habit = $request->user()->habits()->where('id', $id)->first();

        if (!$habit) {
            return response()->json(['message' => 'Habit not found'], 404);
        }

        $habit->delete();
        return response()->json(['message' => 'Habit deleted successfully']);
    }

    // 4. Update Status (Milik sendiri)
    public function toggle(Request $request, $id)
    {
        $habit = $request->user()->habits()->where('id', $id)->first();

        if (!$habit) {
            return response()->json(['message' => 'Habit not found'], 404);
        }

        $habit->status = ($habit->status === 'done') ? 'pending' : 'done';
        $habit->save();

        return response()->json([
            'message' => 'Habit status updated',
            'data' => $habit
        ]);
    }
}