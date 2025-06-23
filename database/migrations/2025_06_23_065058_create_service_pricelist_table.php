<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_pricelists', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // e.g., Haircut, Hair Color (Permanent)
            $table->string('service_name');
            $table->string('length_type')->nullable(); // e.g., Minimum Shoulder, Shoulder Level
            $table->decimal('price', 8, 2)->nullable();
            $table->boolean('is_quoted')->default(false); // true if price is "FOR QUOTE"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_pricelists');
    }
};
