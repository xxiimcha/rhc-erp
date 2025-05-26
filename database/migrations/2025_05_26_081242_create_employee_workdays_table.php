<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeWorkdaysTable extends Migration
{
    public function up()
    {
        Schema::create('employee_workdays', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('day_of_week'); // e.g., Monday
            $table->time('from_time')->nullable();     // optional if storing shift text instead
            $table->time('to_time')->nullable();       // optional if storing shift text instead
            $table->unsignedBigInteger('updated_by')->nullable(); // new column
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete(); // optional behavior
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_workdays');
    }
}
