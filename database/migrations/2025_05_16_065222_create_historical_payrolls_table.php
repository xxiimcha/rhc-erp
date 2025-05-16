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
            $table->decimal('basic_salary', 10, 2)->nullable();
            $table->decimal('net_pay', 10, 2)->nullable();
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
