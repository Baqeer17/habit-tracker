<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Tampilkan halaman utama (Landing Page).
     */
    public function index()
    {
        return view('landing');
    }
}
