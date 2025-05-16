<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterNullableFieldsInEmployeesTable extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('middle_name')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->string('contact_number')->nullable()->change();
            $table->string('gender')->nullable()->change();
            $table->string('philhealth_no')->nullable()->change();
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('middle_name')->nullable(false)->change();
            $table->text('address')->nullable(false)->change();
            $table->string('contact_number')->nullable(false)->change();
            $table->string('gender')->nullable(false)->change();
            $table->string('philhealth_no')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
        });
    }
}
