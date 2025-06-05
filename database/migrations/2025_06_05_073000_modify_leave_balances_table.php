<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('leave_balances', function (Blueprint $table) {
            // Remove old structure (if exists)
            if (Schema::hasColumn('leave_balances', 'leave_type')) {
                $table->dropColumn('leave_type');
            }

            // Add new columns
            $table->integer('vacation_leave')->default(5)->after('year');
            $table->integer('sick_leave')->default(5)->after('vacation_leave');
            $table->integer('emergency_leave')->default(5)->after('sick_leave');
            $table->integer('birthday_leave')->default(1)->after('emergency_leave');
        });
    }

    public function down(): void
    {
        Schema::table('leave_balances', function (Blueprint $table) {
            // Rollback: remove new columns and restore old
            $table->dropColumn(['vacation_leave', 'sick_leave', 'emergency_leave', 'birthday_leave']);
            $table->string('leave_type')->nullable(); // Add back if needed
        });
    }
};
