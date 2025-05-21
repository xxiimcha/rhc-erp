<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Clocking;

class EmployeeClockingController extends Controller
{
    public function index()
    {
        return view('employee.clocking');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|string'
        ]);
    
        $user = Auth::user();
        $now = Carbon::now();
    
        // Check if the user already timed in today
        $clocking = Clocking::where('employee_id', $user->username)
            ->whereDate('time_in', $now->toDateString())
            ->first();
    
        // Determine if it's time in or time out
        $type = (!$clocking) ? 'timein' : (($clocking && !$clocking->time_out) ? 'timeout' : 'completed');
    
        if ($type === 'completed') {
            return back()->with('success', 'You have already completed your time in and out for today.');
        }
    
        // Generate custom image filename
        $timestamp = $now->format('Ymd_His');
        $imageName = "{$user->username}-{$type}-{$timestamp}.jpg";
        $imagePath = 'clockings/' . $imageName;
    
        // Decode base64 and store the image
        $imageData = $request->input('image');
        $base64Str = substr($imageData, strpos($imageData, ',') + 1);
        $fullPath = public_path('clockings/' . $imageName);
        file_put_contents($fullPath, base64_decode($base64Str));
            
        if ($type === 'timein') {
            // Save Time In record
            Clocking::create([
                'employee_id' => $user->username,
                'rfid_number' => null,
                'photo_path' => $imageName,
                'time_in' => $now,
                'status' => 'Present'
            ]);
    
            return back()->with('success', 'Time In recorded successfully.');
        } elseif ($type === 'timeout') {
            // Update Time Out on existing record
            $clocking->update([
                'time_out' => $now,
                'photo_path' => $imageName,
                'hours_worked' => $now->diffInMinutes(Carbon::parse($clocking->time_in)) / 60
            ]);
    
            return back()->with('success', 'Time Out recorded successfully.');
        }
    
        return back()->with('error', 'Unable to process clocking.');
    }    
}
