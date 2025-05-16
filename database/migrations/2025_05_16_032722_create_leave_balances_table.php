<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveBalancesTable extends Migration
{
    public function up(): void
    {
        Schema::create('leave_balances', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name'); // Or use employee_id if related
            $table->year('year');
            $table->integer('total_leaves')->default(15);  // Example default value
            $table->integer('leaves_taken')->default(0);
            $table->integer('remaining_leaves')->default(15);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_balances');
    }
}
