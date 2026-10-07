<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HabitController extends Controller
{
    // 1. Tampilkan Habit milik SAYA saja (Dioptimalkan & Anti N+1)
    public function index(Request $request): JsonResponse
    {
        $habits = $request->user()
            ->habits()
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($habits);
    }

    // 2. Tambah Habit Baru (Dioptimalkan & Tipe Data Terkunci)
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255', 
            'description' => 'nullable|string|max:1000', // Batasi length untuk mencegah payload raksasa
            'icon'        => 'nullable|string|max:50',
            'status'      => 'nullable|string|in:pending,done', 
        ]);

        // Default 'pending' jika tidak diisi
        $validated['status'] = $validated['status'] ?? 'pending';

        // Aman: Eloquent otomatis menyuntikkan user_id
        $habit = $request->user()->habits()->create($validated);

        return response()->json([
            'message' => 'Habit created successfully',
            'data'    => $habit
        ], 201);
    }

    // 3. Hapus Habit (IDOR Patched)
    public function destroy(Request $request, $id): JsonResponse
    {
        // IDOR PATCH: Scoping relasi akan membuang 404 jika ID habit bukan milik user tersebut
        $habit = $request->user()->habits()->findOrFail($id);
        
        $habit->delete();
        
        return response()->json(['message' => 'Habit deleted successfully']);
    }

    // 4. Update Status (IDOR Patched)
    public function toggle(Request $request, $id): JsonResponse
    {
        // IDOR PATCH: Validasi kepemilikan
        $habit = $request->user()->habits()->findOrFail($id);

        $habit->status = ($habit->status === 'done') ? 'pending' : 'done';
        $habit->save();

        return response()->json([
            'message' => 'Habit status updated',
            'data'    => $habit
        ]);
    }
}