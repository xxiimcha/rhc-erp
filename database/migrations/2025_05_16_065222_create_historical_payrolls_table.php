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
        Schema::create('historical_payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('employee_name');
            $table->string('department')->nullable();

            // Earnings
            $table->decimal('basic_salary', 10, 2)->nullable();
            $table->decimal('allowance', 10, 2)->nullable();
            $table->decimal('adjustment', 10, 2)->nullable();
            $table->decimal('ot', 10, 2)->nullable();
            $table->decimal('rdot', 10, 2)->nullable();
            $table->decimal('sh_ot', 10, 2)->nullable();
            $table->decimal('sh', 10, 2)->nullable();
            $table->decimal('lh_rh', 10, 2)->nullable();
            $table->decimal('rnd', 10, 2)->nullable();

            // Deductions
            $table->decimal('tardiness', 10, 2)->nullable();
            $table->decimal('absences', 10, 2)->nullable();
            $table->decimal('sss', 10, 2)->nullable();
            $table->decimal('philhealth', 10, 2)->nullable();
            $table->decimal('pagibig', 10, 2)->nullable();
            $table->decimal('others', 10, 2)->nullable();
            $table->decimal('total_deduction', 10, 2)->nullable();

            // Totals
            $table->decimal('gross', 10, 2)->nullable();
            $table->decimal('net_pay', 10, 2)->nullable();

            // Metadata
            $table->string('sheet')->nullable();
            $table->string('cutoff')->nullable();
            $table->date('period')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historical_payrolls');
    }
};
