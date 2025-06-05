<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Leave;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\LeaveBalance;

class EmployeeLeaveController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $leaves = Leave::where('employee_id', $user->username)->orderBy('created_at', 'desc')->get();

        return view('employee.leaves.index', compact('leaves'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);
    
        $user = Auth::user();
    
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }
    
        Leave::create([
            'employee_id' => $user->username,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'type' => $request->type,
            'reason' => $request->reason,
            'with_pay' => $request->pay === 'with' ? 1 : 0,
            'without_pay' => $request->pay === 'without' ? 1 : 0,
            'status' => 'pending',
            'attachment' => $attachmentPath,
        ]);
    
        return back()->with('success', 'Leave request submitted successfully.');
    }
    
    public function form()
    {
        $user = Auth::user();
        $employee = Employee::where('employee_id', $user->username)->firstOrFail();

        return view('employee.leaves.create', compact('employee'));
    }

    public function checkBalance(Request $request)
    {
        $user = Auth::user();
        $type = $request->query('type');
        $year = now()->year;
    
        $columnMap = [
            'Vacation' => 'vacation_leave',
            'Sick' => 'sick_leave',
            'Emergency' => 'emergency_leave',
            'Birthday' => 'birthday_leave',
        ];
    
        if (!isset($columnMap[$type])) {
            return response()->json(['error' => 'Invalid leave type'], 400);
        }
    
        $column = $columnMap[$type];
    
        $balance = LeaveBalance::where('employee_id', $user->username)
            ->where('year', $year)
            ->first();
    
        $remaining = $balance ? $balance->$column : 0;
    
        return response()->json([
            'remaining' => $remaining
        ]);
    }
    
}
