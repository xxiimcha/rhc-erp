<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payroll_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('daily_rate', 10, 2)->default(0);
            $table->decimal('sss_rate', 5, 2)->default(0);
            $table->decimal('pagibig_rate', 5, 2)->default(0);
            $table->decimal('philhealth_rate', 5, 2)->default(0);
            $table->decimal('ot_multiplier', 5, 2)->default(1.25);
            $table->decimal('late_deduction', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_settings');
    }
};
