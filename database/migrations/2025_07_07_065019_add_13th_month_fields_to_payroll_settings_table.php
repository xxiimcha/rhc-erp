<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add13thMonthFieldsToPayrollSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('payroll_settings', function (Blueprint $table) {
            // Add distribution type if not yet added
            if (!Schema::hasColumn('payroll_settings', 'thirteenth_month_distribution')) {
                $table->string('thirteenth_month_distribution')->default('yearend')->after('thirteenth_month_base');
            }

            // Months field for general usage
            if (!Schema::hasColumn('payroll_settings', 'thirteenth_month_months')) {
                $table->string('thirteenth_month_months')->nullable()->after('thirteenth_month_distribution');
            }

            // Semiannual specific range fields
            if (!Schema::hasColumn('payroll_settings', 'first_half_from')) {
                $table->string('first_half_from', 2)->nullable()->after('thirteenth_month_months');
                $table->string('first_half_to', 2)->nullable()->after('first_half_from');
                $table->string('second_half_from', 2)->nullable()->after('first_half_to');
                $table->string('second_half_to', 2)->nullable()->after('second_half_from');
            }
        });
    }

    public function down()
    {
        Schema::table('payroll_settings', function (Blueprint $table) {
            $table->dropColumn([
                'thirteenth_month_distribution',
                'thirteenth_month_months',
                'first_half_from',
                'first_half_to',
                'second_half_from',
                'second_half_to',
            ]);
        });
    }
}
