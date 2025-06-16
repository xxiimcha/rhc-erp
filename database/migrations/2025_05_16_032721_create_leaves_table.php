<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavesTable extends Migration
{
    public function up(): void
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('leave_date');
            $table->string('type');
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('leaves', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['employee_id']);

            // Then drop the column
            $table->dropColumn('employee_id');

            // Add back employee_name column
            $table->string('employee_name')->after('id');
        });
    }

}
