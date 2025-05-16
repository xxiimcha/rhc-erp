<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('cutoff'); // e.g., '1-15' or '16-30'
            $table->string('period'); // e.g., '2024-12-15'

            // Rate Details
            $table->decimal('basic_pay', 10, 2)->default(0);
            $table->decimal('rate_per_day', 10, 2)->nullable();
            $table->decimal('rate_per_hour', 10, 2)->nullable();
            $table->decimal('rate_ots', 10, 2)->nullable();
            $table->decimal('half_month_pay', 10, 2)->nullable();
            $table->decimal('percent_hourly_rate', 10, 2)->nullable();
            $table->decimal('sh_rate', 10, 2)->nullable();
            $table->decimal('rh_rate', 10, 2)->nullable();
            $table->decimal('restday_rate', 10, 2)->nullable();
            $table->decimal('rnd', 10, 2)->nullable();

            // Computation
            $table->decimal('allowance', 10, 2)->default(0);
            $table->decimal('adjusted_ot', 10, 2)->default(0);
            $table->integer('days_absent')->default(0);
            $table->integer('tardiness')->default(0);

            // Overtime & Bonuses
            $table->decimal('restday_pay', 10, 2)->default(0);
            $table->decimal('special_holiday_pay', 10, 2)->default(0);
            $table->decimal('reg_ot_pay', 10, 2)->default(0);
            $table->decimal('rh_ot_pay', 10, 2)->default(0);
            $table->decimal('sh_ot_pay', 10, 2)->default(0);
            $table->decimal('total_ot', 10, 2)->default(0);

            // Deductions
            $table->decimal('pagibig', 10, 2)->default(0);
            $table->decimal('sss', 10, 2)->default(0);
            $table->decimal('tardiness_deduction', 10, 2)->default(0);

            // Final Computation
            $table->decimal('total_salary', 10, 2)->default(0);
            $table->decimal('thirteenth_month', 10, 2)->default(0); // ✅

            $table->timestamps();

            // Foreign key constraint (optional)
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
