<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('clockings', function (Blueprint $table) {
            $table->integer('late_minutes')->default(0);
            $table->integer('overtime_minutes')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clockings', function (Blueprint $table) {
            //
        });
    }
};
