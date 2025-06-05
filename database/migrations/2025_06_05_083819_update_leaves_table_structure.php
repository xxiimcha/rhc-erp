<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('leaves', function (Blueprint $table) {
            if (Schema::hasColumn('leaves', 'leave_date')) {
                $table->renameColumn('leave_date', 'start_date');
            }

            if (!Schema::hasColumn('leaves', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }

            if (!Schema::hasColumn('leaves', 'with_pay')) {
                $table->integer('with_pay')->default(0)->after('reason');
            }

            if (!Schema::hasColumn('leaves', 'without_pay')) {
                $table->integer('without_pay')->default(0)->after('with_pay');
            }

            if (!Schema::hasColumn('leaves', 'status')) {
                $table->string('status')->default('pending')->after('without_pay');
            }

            if (!Schema::hasColumn('leaves', 'attachment')) {
                $table->string('attachment')->nullable()->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn(['end_date', 'with_pay', 'without_pay', 'status', 'attachment']);

            if (Schema::hasColumn('leaves', 'start_date')) {
                $table->renameColumn('start_date', 'leave_date');
            }
        });
    }
};
