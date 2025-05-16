<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave; // Make sure this model exists
use Illuminate\Support\Facades\Validator;
use App\Models\Employee;

class LeaveController extends Controller
{
    // Show all leave requests
    public function index()
    {
        $leaves = Leave::orderBy('created_at', 'desc')->get();
        $employees = Employee::orderBy('first_name')->get(); // Adjust fields as needed

        return view('hr.leaves.index', compact('leaves', 'employees'));
    }

    // Store a new leave request
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_name' => 'required|string|max:255',
            'leave_type'    => 'required|string|max:100',
            'from_date'     => 'required|date',
            'to_date'       => 'required|date|after_or_equal:from_date',
            'reason'        => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Leave::create([
            'employee_name' => $request->employee_name,
            'leave_type'    => $request->leave_type,
            'from_date'     => $request->from_date,
            'to_date'       => $request->to_date,
            'reason'        => $request->reason,
            'status'        => 'Pending',
        ]);

        return redirect()->route('hr.leaves.index')->with('success', 'Leave request submitted.');
    }

    // Show edit form
    public function edit($id)
    {
        $leave = Leave::findOrFail($id);
        return view('hr.leave-management.edit', compact('leave'));
    }

    // Update leave request
    public function update(Request $request, $id)
    {
        $leave = Leave::findOrFail($id);

        $request->validate([
            'leave_type' => 'required|string|max:100',
            'from_date'  => 'required|date',
            'to_date'    => 'required|date|after_or_equal:from_date',
            'reason'     => 'required|string|max:1000',
            'status'     => 'required|in:Pending,Approved,Rejected',
        ]);

        $leave->update($request->only('leave_type', 'from_date', 'to_date', 'reason', 'status'));

        return redirect()->route('hr.leaves.index')->with('success', 'Leave request updated.');
    }

    // Delete a leave request
    public function destroy($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->delete();

        return redirect()->route('hr.leaves.index')->with('success', 'Leave request deleted.');
    }
}
