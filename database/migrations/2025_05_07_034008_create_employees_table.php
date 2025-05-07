<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->string('email')->unique();
            $table->string('contact_number');
            $table->date('date_of_birth');
            $table->string('gender');
            $table->text('address');

            $table->string('position');
            $table->string('department');
            $table->string('employment_type');
            $table->date('date_hired');

            $table->string('philhealth_no')->nullable();
            $table->string('sss_no')->nullable();
            $table->string('pagibig_no')->nullable();
            $table->string('tin_no')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
