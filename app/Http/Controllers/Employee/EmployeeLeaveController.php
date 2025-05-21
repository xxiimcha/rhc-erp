<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Leave;
use Carbon\Carbon;

class EmployeeLeaveController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $leaves = Leave::where('employee_id', $user->username)->orderBy('created_at', 'desc')->get();

        return view('employee.leaves', compact('leaves'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        Leave::create([
            'employee_id' => $user->username,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Leave request submitted successfully.');
    }
}
