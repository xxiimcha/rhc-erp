<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThirteenthMonthDistributionToPayrollSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('payroll_settings', function (Blueprint $table) {
            $table->enum('thirteenth_month_distribution', ['monthly', 'semiannual', 'yearend'])
                  ->default('semiannual'); // Removed `after('cutoff_basis')`
        });
    }

    public function down()
    {
        Schema::table('payroll_settings', function (Blueprint $table) {
            $table->dropColumn('thirteenth_month_distribution');
        });
    }
}
