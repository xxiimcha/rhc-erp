<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollSettingsTable extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_settings', function (Blueprint $table) {
            $table->id();
        
            // Constants
            $table->integer('annual_months')->default(12);       // Used in: basicPay * 12
            $table->integer('work_days_per_year')->default(261); // Used in: / 261
            $table->integer('work_hours_per_day')->default(8);   // Used in: / 8
            $table->integer('minutes_per_day')->default(480);    // Used in: / 480
            $table->integer('monthly_working_days')->default(22);
            $table->integer('monthly_working_hours')->default(176);
        
            // Multipliers
            $table->decimal('ot_multiplier', 5, 2)->default(1.25);           // Not in formula but generally used
            $table->decimal('night_diff_percent', 5, 2)->default(10.00);     // 10%
            $table->decimal('sh_rate_percent', 5, 2)->default(30.00);        // shRate = ratePerDay * 0.30
            $table->decimal('rh_rate_multiplier', 5, 2)->default(2.6);       // rhRate = ratePerHour * 2.6
            $table->decimal('ots_rate_multiplier', 5, 2)->default(2.00);     // rateOTS = (ratePerDay * 2) / 8
            $table->decimal('rest_day_extra_percent', 5, 2)->default(30.00); // restDayRate = ratePerDay + (ratePerDay * 0.30)
            $table->decimal('percent_hourly_rate', 5, 2)->default(25.00);    // used in your JS
        
            // Deductions
            $table->decimal('late_deduction_base_divisor', 6, 2)->default(480.00); // ratePerDay / X
            $table->decimal('philhealth_rate', 5, 2)->default(4.50);
            $table->decimal('pagibig_rate', 5, 2)->default(2.00);
        
            // For 13th month base
            $table->integer('thirteenth_month_base')->default(12);
        
            // Optional global fallback
            $table->decimal('default_daily_rate', 10, 2)->default(0);
        
            $table->timestamps();
        });        
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_settings');
    }
}
