<?php

namespace App\Http\Controllers;

use App\Models\PrayerLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrayerController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        // 1. Cek Tanggal (Default: Hari Ini)
        $targetDate = $request->has('date') ? Carbon::parse($request->date) : Carbon::now();

        // 2. Proteksi Masa Depan (Balikin ke hari ini kalau user maksa ke masa depan)
        if ($targetDate->isFuture()) {
            return redirect()->route('prayers.index');
        }

        // 3. Tentukan Awal & Akhir Minggu
        $startOfWeek = $targetDate->copy()->startOfWeek();
        $endOfWeek = $targetDate->copy()->endOfWeek();

        // 4. AMBIL DATA & PAKSA FORMAT KUNCINYA (Ini Solusi Masalah Mas)
        $logs = PrayerLog::where('user_id', Auth::id())
            ->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
            ->get()
            ->keyBy(function($item) {
                // KITA PAKSA formatnya jadi YYYY-MM-DD agar cocok dengan View
                return Carbon::parse($item->date)->format('Y-m-d');
            });

        // Debugging: Kalau mau ngecek isi logs, bisa uncomment baris bawah ini
        // dd($logs); 

        return view('prayers.pray_wajib', compact('logs', 'startOfWeek', 'targetDate'));
    }

    public function ghairuMuakkad(Request $request)
    {
        Carbon::setLocale('id');
        $targetDate = $request->has('date') ? Carbon::parse($request->date) : Carbon::now();

        if ($targetDate->isFuture()) {
            return redirect()->route('shalat-sunnah.ghairu-muakkad');
        }

        // Calendar Data
        $startOfWeek = $targetDate->copy()->startOfWeek();
        $endOfWeek = $targetDate->copy()->endOfWeek();

        $logs = PrayerLog::where('user_id', Auth::id())
            ->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
            ->get()
            ->keyBy(function($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });

        return view('shalat-sunnah.ghairu-muakkad', compact('logs', 'targetDate'));
    }

    public function rawatib(Request $request)
    {
        Carbon::setLocale('id');

        // 1. Cek Tanggal (Default: Hari Ini) - Fokus ke Rawatib (biasanya harian, tapi kita kasih fitur tanggal juga gapapa)
        $targetDate = $request->has('date') ? Carbon::parse($request->date) : Carbon::now();

        // 2. Proteksi Masa Depan
        if ($targetDate->isFuture()) {
            return redirect()->route('shalat-sunnah.rawatib');
        }

        // 3. Ambil Data HARI INI (atau tanggal terpilih)
        // Rawatib butuh data harian untuk daily progress reset
        $log = PrayerLog::where('user_id', Auth::id())
            ->whereDate('date', '=', $targetDate->format('Y-m-d'))
            ->first();

        // Kita juga bisa ambil mingguan kalau mau kalender, tapi user minta daily progress.
        // Untuk kalender widget, kita butuh data bulanan/mingguan sebenarnya.
        // Mari kita ambil mingguan juga seperti shalat wajib biar kalendernya jalan (kalau widget kalender dipake).
        $startOfWeek = $targetDate->copy()->startOfWeek();
        $endOfWeek = $targetDate->copy()->endOfWeek();

        $logs = PrayerLog::where('user_id', Auth::id())
            ->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
            ->get()
            ->keyBy(function($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });

        return view('shalat-sunnah.rawatib', compact('logs', 'log', 'targetDate'));
    }

    public function toggle(Request $request)
    {
        // Validasi - Tambahkan kolom sunnah
        $request->validate([
            'date' => 'required|date',
            'prayer' => 'required|in:fajr,dhuhr,asr,maghrib,isha,q_subuh,q_dhuhr,b_dhuhr,b_maghrib,q_isha,b_isha,dhuha,tahajud,witir',
            'status' => 'required|boolean'
        ]);

        // Cek Masa Depan
        if (Carbon::parse($request->date)->isFuture()) {
            return response()->json(['success' => false, 'message' => 'Masa depan terkunci'], 400);
        }

        // SIMPAN DATA (Auto Save)
        // Pastikan User ID + Tanggal menjadi kunci pencarian
        $log = PrayerLog::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'date' => $request->date, // Pastikan format Y-m-d dari frontend
            ],
            [
                $request->prayer => $request->status
            ]
        );

        return response()->json(['success' => true, 'data' => $log]);
    }
}