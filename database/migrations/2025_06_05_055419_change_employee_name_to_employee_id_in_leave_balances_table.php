<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeEmployeeNameToEmployeeIdInLeaveBalancesTable extends Migration
{
    public function up()
    {
        Schema::table('leave_balances', function (Blueprint $table) {
            $table->dropColumn('employee_name');
            $table->unsignedBigInteger('employee_id')->after('id');
        });
    }

    public function down()
    {
        Schema::table('leave_balances', function (Blueprint $table) {
            $table->dropColumn('employee_id');
            $table->string('employee_name', 255)->after('id');
        });
    }
}
