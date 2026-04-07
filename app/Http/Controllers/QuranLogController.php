<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuranLog;
use App\Services\QuranProgressService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class QuranLogController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'start_surah' => 'required|integer|min:1|max:114',
            'start_ayat' => 'required|integer|min:1',
            'end_surah' => 'required|integer|min:1|max:114',
            'end_ayat' => 'required|integer|min:1',
            'date' => 'required|date'
        ]);

        $totalAyat = QuranProgressService::calculateTotalAyat(
            $request->start_surah,
            $request->start_ayat,
            $request->end_surah,
            $request->end_ayat
        );

        if ($totalAyat <= 0) {
            return response()->json(['message' => 'Rentang ayat tidak valid.'], 422);
        }

        QuranLog::create([
            'user_id' => Auth::id(),
            'start_surah' => $request->start_surah,
            'start_ayat' => $request->start_ayat,
            'end_surah' => $request->end_surah,
            'end_ayat' => $request->end_ayat,
            'total_ayat' => $totalAyat,
            'date' => $request->date
        ]);

        return response()->json(['message' => 'Jurnal berhasil disimpan.', 'total' => $totalAyat]);
    }

    public function weeklyProgress()
    {
        Carbon::setLocale('id');
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);
        
        // Optimize: Filter directly in Database
        $logs = QuranLog::where('user_id', Auth::id())
            ->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
            ->get();

        $progress = [];
        $current = $startOfWeek->copy();

        while ($current->lte($endOfWeek)) {
            $dateStr = $current->format('Y-m-d');
            
            $dayLogs = $logs->filter(function ($log) use ($dateStr) {
                return str_starts_with($log->date, $dateStr);
            })->values();

            $progress[] = [
                'day' => $current->locale('id')->isoFormat('dd'),
                'date' => $dateStr,
                'total' => $dayLogs->sum('total_ayat'),
                'logs' => $dayLogs
            ];

            $current->addDay();
        }

        return response()->json($progress);
    }
}
