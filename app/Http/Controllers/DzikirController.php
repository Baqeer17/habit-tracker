<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DzikirLog;
use Carbon\Carbon;

class DzikirController extends Controller
{
    public function index()
    {
        $todayStr = Carbon::now()->format('Y-m-d');
        $log = DzikirLog::where('user_id', auth()->id())
                    ->where('date', $todayStr)
                    ->first();

        // Cek status saat ini
        $pagiCompleted = $log ? $log->pagi_completed : false;
        $petangCompleted = $log ? $log->petang_completed : false;
        $salatCompleted = $log ? $log->salat_completed : false;

        return view('dzikir', compact('pagiCompleted', 'petangCompleted', 'salatCompleted'));
    }

    public function logProgress(Request $request)
    {
        $request->validate([
            'type' => 'required|in:pagi,petang,salat',
            'details' => 'array'
        ]);

        $todayStr = Carbon::now()->format('Y-m-d');
        
        $log = DzikirLog::firstOrCreate(
            ['user_id' => auth()->id(), 'date' => $todayStr],
            [
                'pagi_completed' => false, 
                'petang_completed' => false, 
                'salat_completed' => false
            ]
        );

        $typeField = $request->type . '_completed';
        $detailsField = $request->type . '_details';
        
        $log->$typeField = true;
        
        if ($request->has('details')) {
            $log->$detailsField = $request->details;
        }

        $log->save();

        return response()->json(['success' => true]);
    }
}
