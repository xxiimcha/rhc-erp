<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clockings', function (Blueprint $table) {
            $table->date('clock_date')->after('time_out');
        });
    }
    
    public function down(): void
    {
        Schema::table('clockings', function (Blueprint $table) {
            $table->dropColumn('clock_date');
        });
    }
};
