<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyLeavesTableUseEmployeeId extends Migration
{
    public function up()
    {
        Schema::table('leaves', function (Blueprint $table) {
            // Drop old column
            if (Schema::hasColumn('leaves', 'employee_name')) {
                $table->dropColumn('employee_name');
            }

            // Add employee_id with FK constraint
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn('employee_id');
            $table->string('employee_name')->after('id');
        });
    }
}
