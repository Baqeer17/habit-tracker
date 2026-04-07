<?php

namespace App\Http\Controllers;

use App\Models\ZakatHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ZakatController extends Controller
{
    public function index()
    {
        // Get the zakat history for logged in user, newest first
        $histories = ZakatHistory::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('zakat', compact('histories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_zakat' => 'required|string|in:penghasilan,fitrah',
            'nominal'     => 'required|numeric|min:0',
        ]);

        ZakatHistory::create([
            'user_id'     => Auth::id(),
            'jenis_zakat' => $request->jenis_zakat,
            'nominal'     => $request->nominal,
        ]);

        return redirect()->route('zakat.index')->with('success', 'Riwayat zakat berhasil disimpan.');
    }
}
