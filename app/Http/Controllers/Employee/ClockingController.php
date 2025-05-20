<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\EmployeeTimesheet;

class ClockingController extends Controller
{
    public function index()
    {
        return view('employee.clocking');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:in,out',
            'image' => 'required|string'
        ]);

        $user = Auth::user();
        $imageData = $request->input('image');
        $imageName = Str::uuid() . '.jpg';
        $imagePath = 'clockings/' . $imageName;

        // Decode base64 and save to public storage
        $base64Str = substr($imageData, strpos($imageData, ',') + 1);
        Storage::disk('public')->put($imagePath, base64_decode($base64Str));

        // Insert into timesheet table
        EmployeeTimesheet::create([
            'employee_id' => $user->username, // Assuming employee_id == username
            'type' => $request->type,
            'timestamp' => Carbon::now(),
            'photo' => $imageName,
        ]);

        return redirect()->back()->with('success', 'Successfully recorded your ' . ucfirst($request->type));
    }
}
