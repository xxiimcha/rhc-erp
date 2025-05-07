<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clockings', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id'); // You can change this to foreignId if using relations
            $table->string('rfid_number');
            $table->timestamp('time_in')->nullable();
            $table->timestamp('time_out')->nullable();
            $table->string('status')->default('on-time'); // values: 'on-time', 'late'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clockings');
    }
};
