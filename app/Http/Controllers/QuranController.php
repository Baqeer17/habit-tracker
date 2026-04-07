<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuranController extends Controller
{
    public function index()
    {
        return view('quran');
    }

    public function show($nomor)
    {
        return view('quran-detail', compact('nomor'));
    }

    public function juz($nomor)
    {
        return view('quran-juz', compact('nomor'));
    }
}
