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
        $employee = Employee::where('employee_id', $user->username)->first();

        $leaves = Leave::where('employee_id', $employee->id)->orderBy('created_at', 'desc')->get();

        return view('employee.leaves.index', compact('leaves'));
    }

    public function store(Request $request)
    {
        \Log::info('Leave request attempt:', $request->all());

        $request->validate([
            'type' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $user = Auth::user();

        // Safely get employee numeric ID
        $employee = Employee::where('employee_id', $user->username)->first();
        if (!$employee) {
            \Log::error('Employee not found for username: ' . $user->username);
            return back()->with('error', 'Employee record not found.');
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
            \Log::info('Attachment uploaded to:', ['path' => $attachmentPath]);
        }

        try {
            Leave::create([
                'employee_id'   => $employee->id, // this should match the PK in your employees table
                'start_date'    => $request->start_date,
                'end_date'      => $request->end_date,
                'type'          => $request->type,
                'reason'        => $request->reason,
                'with_pay'      => $request->has('with_pay') ? 1 : 0,
                'without_pay'   => $request->has('without_pay') ? 1 : 0,
                'status'        => 'pending',
                'attachment'    => $attachmentPath,
            ]);

            \Log::info('Leave request successfully inserted.');
            return redirect()->route('employee.leaves.index')->with('success', 'Leave request submitted successfully.');
        } catch (\Exception $e) {
            \Log::error('Leave insert failed:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to submit leave request.');
        }
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
    
    public function cancel($id)
    {
        $user = Auth::user();
    
        // Fetch the employee based on user
        $employee = Employee::where('employee_id', $user->username)->first();
    
        if (!$employee) {
            return back()->with('error', 'Employee not found.');
        }
    
        // Find the pending leave belonging to the employee
        $leave = Leave::where('id', $id)
            ->where('employee_id', $employee->id)
            ->where('status', 'pending')
            ->first();
    
        if (!$leave) {
            return back()->with('error', 'Leave not found or already processed.');
        }
    
        // Mark it as cancelled (instead of deleting)
        $leave->update(['status' => 'cancelled']);
    
        return back()->with('success', 'Leave request canceled successfully.');
    }
}
