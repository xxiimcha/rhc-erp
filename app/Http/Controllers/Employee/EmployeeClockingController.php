<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Models\Clocking;

class EmployeeClockingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now('Asia/Manila');
        $today = $now->toDateString();
    
        $clocking = Clocking::where('employee_id', $user->username)
            ->whereDate('time_in', $today)
            ->first();
    
        $hasCompletedToday = $clocking && $clocking->time_out !== null;
    
        return view('employee.clocking', compact('hasCompletedToday'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|string'
        ]);

        $user = Auth::user();
        $now = Carbon::now('Asia/Manila');
        $today = $now->toDateString();

        $startTime  = Carbon::parse($today . ' 08:00:00', 'Asia/Manila');
        $graceTime  = Carbon::parse($today . ' 08:05:00', 'Asia/Manila');
        $endTime    = Carbon::parse($today . ' 17:00:00', 'Asia/Manila');

        // Check if user has a clocking record for today
        $clocking = Clocking::where('employee_id', $user->username)
            ->whereDate('time_in', $today)
            ->first();

        $type = (!$clocking) ? 'timein' : ((!$clocking->time_out) ? 'timeout' : 'completed');

        if ($type === 'completed') {
            return back()->with('success', 'You have already completed your time in and out for today.');
        }

        // Generate image file name
        $timestamp = $now->format('Ymd_His');
        $imageName = "{$user->username}-{$type}-{$timestamp}.jpg";
        $imagePath = public_path('clockings/' . $imageName);

        // Create directory if not exists
        if (!File::exists(public_path('clockings'))) {
            File::makeDirectory(public_path('clockings'), 0755, true);
        }

        // Decode and save image
        $base64Str = substr($request->input('image'), strpos($request->input('image'), ',') + 1);
        file_put_contents($imagePath, base64_decode($base64Str));

        if ($type === 'timein') {
            $status = 'on-time';
            $lateMinutes = 0;

            if ($now->greaterThan($graceTime)) {
                $status = 'late';
                $lateMinutes = $now->diffInMinutes($startTime);
            }

            Clocking::create([
                'employee_id'   => $user->username,
                'rfid_number'   => null,
                'photo_path'    => $imageName,
                'time_in'       => $now,
                'status'        => $status,
                'late_minutes'  => $lateMinutes
            ]);

            return back()->with('success', 'Time In recorded successfully.');
        }

        if ($type === 'timeout') {
            $overtimeMinutes = 0;

            if ($now->greaterThan($endTime)) {
                $overtimeMinutes = $now->diffInMinutes($endTime);
            }

            $timeIn = Carbon::parse($clocking->time_in, 'Asia/Manila');

            $clocking->update([
                'time_out'         => $now,
                'photo_path'       => $imageName,
                'hours_worked'     => round($now->diffInMinutes($timeIn) / 60, 2),
                'overtime_minutes' => $overtimeMinutes
            ]);

            return back()->with('success', 'Time Out recorded successfully.');
        }

        return back()->with('error', 'Unable to process clocking.');
    }
}
