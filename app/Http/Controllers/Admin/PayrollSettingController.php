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
            'thirteenth_month_distribution' => 'nullable|string',
            'thirteenth_month_base' => 'nullable|numeric',
            'thirteenth_month_months' => 'nullable|string',
        ]);

        // Conditionally validate semiannual ranges
        if ($request->thirteenth_month_distribution === 'semiannual') {
            $request->validate([
                'first_half_from' => 'required|date_format:m-d',
                'first_half_to' => 'required|date_format:m-d|after_or_equal:first_half_from',
                'second_half_from' => 'required|date_format:m-d',
                'second_half_to' => 'required|date_format:m-d|after_or_equal:second_half_from',
            ]);

            $validated = array_merge($validated, $request->only([
                'first_half_from',
                'first_half_to',
                'second_half_from',
                'second_half_to',
            ]));
        } else {
            // Set nulls if not semiannual
            $validated = array_merge($validated, [
                'first_half_from' => null,
                'first_half_to' => null,
                'second_half_from' => null,
                'second_half_to' => null,
            ]);
        }

        $settings = PayrollSetting::firstOrCreate([]);
        $settings->update($validated);

        return redirect()->route('admin.settings.payroll')->with('success', 'Payroll settings updated successfully.');
    }
}
