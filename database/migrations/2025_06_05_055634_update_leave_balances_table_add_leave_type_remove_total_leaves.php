<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLeaveBalancesTableAddLeaveTypeRemoveTotalLeaves extends Migration
{
    public function up()
    {
        Schema::table('leave_balances', function (Blueprint $table) {
            $table->string('leave_type')->after('employee_id'); // You can change the position as needed
            $table->dropColumn('total_leaves');
        });
    }

    public function down()
    {
        Schema::table('leave_balances', function (Blueprint $table) {
            $table->integer('total_leaves')->default(15)->after('year');
            $table->dropColumn('leave_type');
        });
    }
}
