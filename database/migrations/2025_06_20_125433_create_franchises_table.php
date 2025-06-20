<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('franchises', function (Blueprint $table) {
            $table->id();
            
            // Franchise Details
            $table->string('branch_code')->unique();
            $table->string('branch');
            $table->string('region');
            $table->string('location');
            $table->unsignedBigInteger('variant_id');
            $table->date('franchise_date');
            $table->date('end_of_contract');

            // Franchisee Details
            $table->string('franchisee_name');
            $table->string('email_address');
            $table->string('contact_number');
            $table->date('birthday');
            $table->string('home_address');

            $table->timestamps();

            // Foreign keys
            $table->foreign('variant_id')->references('id')->on('variants')->onDelete('cascade');
        });

        Schema::create('franchise_staff', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('franchise_id');
            $table->string('staff_name');
            $table->string('staff_designation');
            $table->timestamps();

            $table->foreign('franchise_id')->references('id')->on('franchises')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('franchise_staff');
        Schema::dropIfExists('franchises');
    }
};
