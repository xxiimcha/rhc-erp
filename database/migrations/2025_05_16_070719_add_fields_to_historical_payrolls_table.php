<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('historical_payrolls', function (Blueprint $table) {
            $table->decimal('allowance', 10, 2)->nullable()->after('basic_salary');
            $table->decimal('gross', 10, 2)->nullable()->after('allowance');
            $table->decimal('sss', 10, 2)->nullable()->after('gross');
            $table->decimal('philhealth', 10, 2)->nullable()->after('sss');
            $table->decimal('pagibig', 10, 2)->nullable()->after('philhealth');
        });
    }

    public function down(): void
    {
        Schema::table('historical_payrolls', function (Blueprint $table) {
            $table->dropColumn(['allowance', 'gross', 'sss', 'philhealth', 'pagibig']);
        });
    }
};
