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
        Schema::table('leaves', function (Blueprint $table) {
            $table->unsignedBigInteger('reviewed_by')->nullable()->after('status');
            $table->unsignedBigInteger('approved_by')->nullable()->after('reviewed_by');

            // Optional: Add foreign key constraints if 'users' table is used
            // $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
            // $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('leaves', function (Blueprint $table) {
            // Drop columns and foreign keys if you added them
            // $table->dropForeign(['reviewed_by']);
            // $table->dropForeign(['approved_by']);
            $table->dropColumn(['reviewed_by', 'approved_by']);
        });
    }

};
