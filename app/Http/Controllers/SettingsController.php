<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\PrayerLog;
use App\Models\QuranLog;
use App\Models\DzikirLog;

class SettingsController extends Controller
{
    // ─────────────── INDEX ───────────────
    public function index()
    {
        return view('settings', [
            'user' => auth()->user(),
        ]);
    }

    // ─────────────── PUSH SUBSCRIPTIONS ───────────────
    public function savePushSubscription(Request $request)
    {
        $request->validate([
            'endpoint'    => 'required|unique:push_subscriptions,endpoint,' . auth()->id() . ',user_id',
            'keys.p256dh' => 'required',
            'keys.auth'   => 'required',
        ]);

        $user = auth()->user();

        // Update or Create the subscription endpoint
        \App\Models\PushSubscription::updateOrCreate(
            ['endpoint' => $request->endpoint, 'user_id' => $user->id],
            [
                'public_key' => $request->keys['p256dh'],
                'auth_token' => $request->keys['auth'],
            ]
        );

        return response()->json(['success' => true]);
    }

    // ─────────────── UPDATE (profile/preferences) ───────────────
    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'              => 'sometimes|string|max:255',
            'bio'               => 'sometimes|nullable|string|max:1000',
            'phone'             => 'sometimes|nullable|string|max:20',
            'gender'            => 'sometimes|nullable|string',
            'location_name'     => 'sometimes|nullable|string',
            'language'          => 'sometimes|string|in:id,en',
            'theme'             => 'sometimes|string',
            'font_size'         => 'sometimes|integer|min:1|max:5',
            'azan_notification' => 'sometimes|boolean',
            'silent_mode'       => 'sometimes|boolean',
            'tahajud_time'      => 'sometimes|nullable|string',
            'duha_time'         => 'sometimes|nullable|string',
            'tilawah_time'      => 'sometimes|nullable|string',
            'avatar'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Handle remove avatar
        if ($request->input('remove_avatar') == '1') {
            if ($user->avatar && !\Illuminate\Support\Str::startsWith($user->avatar, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = null;
        } 
        // Handle avatar upload
        else if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada dan bukan dari server eksternal (Google dsb)
            if ($user->avatar && !\Illuminate\Support\Str::startsWith($user->avatar, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            
            // Simpan yang baru
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        // Handle unchecked checkboxes (absent from request = false)
        if ($request->has('activeTab')) {
            if (in_array($request->activeTab, ['notifikasi', 'personalisasi'])) {
                $data['azan_notification'] = $request->boolean('azan_notification');
                $data['silent_mode']       = $request->boolean('silent_mode');
            }
        }

        $user->update($data);

        return back()->with('success', 'Pengaturan berhasil diperbarui!')->with('activeTab', $request->activeTab);
    }

    // ─────────────── CHANGE PASSWORD ───────────────
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'new_password.required'     => 'Password baru wajib diisi.',
            'new_password.min'          => 'Password baru minimal 8 karakter.',
            'new_password.confirmed'    => 'Konfirmasi password tidak cocok.',
        ]);

        $user = auth()->user();

        // Pastikan password lama benar
        if (! Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password lama tidak sesuai.'])
                ->with('activeTab', 'akun')
                ->with('open_password_modal', true);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return redirect()->route('settings.index')
            ->with('success', 'Password berhasil diubah! Silakan login kembali.')
            ->with('activeTab', 'akun');
    }

    // ─────────────── EXPORT CSV ───────────────
    public function exportCsv()
    {
        $user   = auth()->user();
        $userId = $user->id;

        $filename = 'mahabba-spiritual-logs-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($userId, $user) {
            $handle = fopen('php://output', 'w');

            // BOM UTF-8 agar Excel membaca karakter Indonesia dengan benar
            fputs($handle, "\xEF\xBB\xBF");

            // ─── META ───
            fputcsv($handle, ['=== MahabBa Spiritual Logs ===']);
            fputcsv($handle, ['User', $user->name]);
            fputcsv($handle, ['Email', $user->email]);
            fputcsv($handle, ['Tanggal Ekspor', now()->format('d F Y, H:i')]);
            fputcsv($handle, []);

            // ─── PRAYER LOGS ───
            fputcsv($handle, ['--- CATATAN SHALAT ---']);
            fputcsv($handle, ['Tanggal', 'Subuh', 'Dzuhur', 'Asar', 'Maghrib', 'Isya',
                'Dhuha', 'Tahajud', 'Witir', 'Q.Subuh', 'Q.Dzuhur', 'B.Dzuhur', 'B.Maghrib', 'Q.Isya', 'B.Isya']);

            $prayers = PrayerLog::where('user_id', $userId)
                ->orderBy('date')
                ->get();

            foreach ($prayers as $p) {
                fputcsv($handle, [
                    $p->date, $p->subuh ? '✓' : '-', $p->dzuhur ? '✓' : '-',
                    $p->asar ? '✓' : '-', $p->maghrib ? '✓' : '-', $p->isya ? '✓' : '-',
                    $p->dhuha ? '✓' : '-', $p->tahajud ? '✓' : '-', $p->witir ? '✓' : '-',
                    $p->q_subuh ? '✓' : '-', $p->q_dhuhr ? '✓' : '-', $p->b_dhuhr ? '✓' : '-',
                    $p->b_maghrib ? '✓' : '-', $p->q_isha ? '✓' : '-', $p->b_isha ? '✓' : '-',
                ]);
            }

            fputcsv($handle, []);

            // ─── QURAN LOGS ───
            fputcsv($handle, ['--- CATATAN TILAWAH AL-QURAN ---']);
            fputcsv($handle, ['Tanggal', 'Dari Surah', 'Ayat Mulai', 'Hingga Surah', 'Ayat Selesai', 'Total Ayat']);

            $quranLogs = QuranLog::where('user_id', $userId)
                ->orderBy('date')
                ->get();

            foreach ($quranLogs as $q) {
                fputcsv($handle, [
                    $q->date,
                    'Surah ' . $q->start_surah,
                    $q->start_ayat,
                    'Surah ' . $q->end_surah,
                    $q->end_ayat,
                    $q->total_ayat . ' ayat',
                ]);
            }

            fputcsv($handle, []);

            // ─── DZIKIR LOGS ───
            fputcsv($handle, ['--- CATATAN DZIKIR ---']);
            fputcsv($handle, ['Tanggal', 'Tipe', 'Selesai']);

            $dzikirLogs = DzikirLog::where('user_id', $userId)
                ->orderBy('date')
                ->get();

            foreach ($dzikirLogs as $d) {
                // Satu row per hari, 3 kolom tipe
                fputcsv($handle, [
                    $d->date,
                    'Pagi',
                    $d->pagi_completed ? 'Ya' : 'Tidak',
                ]);
                fputcsv($handle, [
                    $d->date,
                    'Petang',
                    $d->petang_completed ? 'Ya' : 'Tidak',
                ]);
                fputcsv($handle, [
                    $d->date,
                    'Salat',
                    $d->salat_completed ? 'Ya' : 'Tidak',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ─────────────── DELETE ACCOUNT ───────────────
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'confirm_delete' => 'required|in:HAPUS',
        ], [
            'confirm_delete.required' => 'Ketik HAPUS untuk konfirmasi.',
            'confirm_delete.in'       => 'Ketik kata "HAPUS" dengan huruf kapital.',
        ]);

        $user = auth()->user();

        // Hapus semua data terkait
        PrayerLog::where('user_id', $user->id)->delete();
        QuranLog::where('user_id', $user->id)->delete();
        DzikirLog::where('user_id', $user->id)->delete();

        // Hapus habits jika ada
        if (method_exists($user, 'habits')) {
            $user->habits()->delete();
        }

        // Logout & hapus akun
        $userId = $user->id;
        Auth::logout();
        \App\Models\User::destroy($userId);

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/login')->with('success', 'Akun berhasil dihapus. Semoga bertemu kembali!');
    }

    // ─────────────── GENERATE REPORT ───────────────
    public function generateReport(Request $request)
    {
        $user   = auth()->user();
        $userId = $user->id;
        $period = $request->get('period', 'bulan_ini');

        // Tentukan rentang tanggal
        [$startDate, $endDate, $periodLabel] = $this->getPeriodRange($period);

        // ── Data Shalat ──
        $prayerQuery = PrayerLog::where('user_id', $userId)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('date');

        $prayers = $prayerQuery->get();

        $prayerStats = [
            'total_days'    => $prayers->count(),
            'subuh'         => $prayers->where('subuh', true)->count(),
            'dzuhur'        => $prayers->where('dzuhur', true)->count(),
            'asar'          => $prayers->where('asar', true)->count(),
            'maghrib'       => $prayers->where('maghrib', true)->count(),
            'isya'          => $prayers->where('isya', true)->count(),
            'perfect_days'  => $prayers->filter(fn($p) =>
                $p->subuh && $p->dzuhur && $p->asar && $p->maghrib && $p->isya
            )->count(),
            // Sunnah
            'dhuha'         => $prayers->where('dhuha', true)->count(),
            'tahajud'       => $prayers->where('tahajud', true)->count(),
            'witir'         => $prayers->where('witir', true)->count(),
        ];

        $totalPossibleWajib = $prayers->count() * 5;
        $totalDoneWajib = $prayerStats['subuh'] + $prayerStats['dzuhur'] + $prayerStats['asar']
            + $prayerStats['maghrib'] + $prayerStats['isya'];
        $prayerStats['completion_rate'] = $totalPossibleWajib > 0
            ? round(($totalDoneWajib / $totalPossibleWajib) * 100)
            : 0;

        // ── Data Tilawah ──
        $quranLogs = QuranLog::where('user_id', $userId)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('date')
            ->get();

        $quranStats = [
            'total_sessions' => $quranLogs->count(),
            'total_ayat'     => $quranLogs->sum('total_ayat'),
            'active_days'    => $quranLogs->pluck('date')->unique()->count(),
            // Estimasi halaman: ~15 ayat/halaman (rata-rata Quran standar)
            'total_pages'    => $quranLogs->sum('total_ayat') > 0
                ? round($quranLogs->sum('total_ayat') / 15)
                : 0,
        ];

        // ── Data Dzikir ──
        // Skema: 1 record/hari dengan kolom pagi_completed, petang_completed, salat_completed
        $dzikirLogs = DzikirLog::where('user_id', $userId)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('date')
            ->get();

        $dzikirStats = [
            'total_days'   => $dzikirLogs->count(),
            'pagi'         => $dzikirLogs->where('pagi_completed', true)->count(),
            'petang'       => $dzikirLogs->where('petang_completed', true)->count(),
            'salat'        => $dzikirLogs->where('salat_completed', true)->count(),
            'active_days'  => $dzikirLogs->filter(fn($d) =>
                $d->pagi_completed || $d->petang_completed || $d->salat_completed
            )->count(),
            'total_sessions' => $dzikirLogs->sum(fn($d) =>
                ($d->pagi_completed ? 1 : 0) + ($d->petang_completed ? 1 : 0) + ($d->salat_completed ? 1 : 0)
            ),
        ];

        // ── Streak ──
        $streak = $this->calculateStreakInPeriod($userId, $startDate, $endDate);

        return view('settings.report', compact(
            'user', 'period', 'periodLabel',
            'startDate', 'endDate',
            'prayers', 'prayerStats',
            'quranLogs', 'quranStats',
            'dzikirLogs', 'dzikirStats',
            'streak'
        ));
    }

    // ─────────────── HELPERS ───────────────

    private function getPeriodRange(string $period): array
    {
        $now = Carbon::now();

        $ranges = [
            'hari_ini'  => [$now->copy()->startOfDay(),   $now->copy()->endOfDay(),    'Hari Ini (' . $now->format('d F Y') . ')'],
            'bulan_ini' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth(),  'Bulan Ini (' . $now->format('F Y') . ')'],
            '3_bulan'   => [$now->copy()->subMonths(3),   $now->copy(),               '3 Bulan Terakhir'],
            '6_bulan'   => [$now->copy()->subMonths(6),   $now->copy(),               '6 Bulan Terakhir'],
            'tahun_ini' => [$now->copy()->startOfYear(),  $now->copy()->endOfYear(),   'Tahun Ini (' . $now->year . ')'],
            '3_tahun'   => [$now->copy()->subYears(3),    $now->copy(),               '3 Tahun Terakhir'],
            '5_tahun'   => [$now->copy()->subYears(5),    $now->copy(),               '5 Tahun Terakhir'],
            'semua'     => [Carbon::createFromDate(2020, 1, 1), $now->copy(),         'Semua Data'],
        ];

        return $ranges[$period] ?? $ranges['bulan_ini'];
    }

    private function calculateStreakInPeriod($userId, Carbon $start, Carbon $end): int
    {
        $streak = 0;
        $date   = $end->copy()->startOfDay();
        $limit  = $start->copy()->startOfDay();

        while ($date->gte($limit)) {
            $dateStr   = $date->format('Y-m-d');
            $hasPrayer = PrayerLog::where('user_id', $userId)->where('date', $dateStr)->exists();
            $hasQuran  = QuranLog::where('user_id', $userId)->where('date', $dateStr)->exists();
            // Dzikir: cek ada hari tersebut dengan minimal 1 tipe completed
            $dzikirRow = DzikirLog::where('user_id', $userId)->where('date', $dateStr)->first();
            $hasDzikir = $dzikirRow && (
                $dzikirRow->pagi_completed || $dzikirRow->petang_completed || $dzikirRow->salat_completed
            );

            if ($hasPrayer || $hasQuran || $hasDzikir) {
                $streak++;
            }
            $date->subDay();
        }

        return $streak;
    }
}
