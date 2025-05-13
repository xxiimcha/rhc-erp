<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PayrollSetting;
use App\Models\SssBracket;

class PayrollSettingController extends Controller
{
    public function edit()
    {
        $settings = PayrollSetting::first();
        $sssBrackets = SssBracket::all(); // load your table
        return view('admin.settings.payroll', compact('settings', 'sssBrackets'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'daily_rate' => 'required|numeric',
            'sss_rate' => 'required|numeric',
            'pagibig_rate' => 'required|numeric',
            'philhealth_rate' => 'required|numeric',
            'ot_multiplier' => 'required|numeric',
            'late_deduction' => 'required|numeric',
        ]);

        $settings = PayrollSetting::firstOrCreate([]);
        $settings->update($validated);

        return redirect()->route('admin.settings.payroll')->with('success', 'Payroll settings updated successfully.');
    }

}
