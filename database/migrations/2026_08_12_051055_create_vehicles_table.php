<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number')->unique();
            $table->string('vehicle_type'); // Truck, Van, etc
            $table->string('brand')->nullable();
            $table->decimal('capacity', 10, 2)->default(0); // Kapasitas Kg
            $table->string('status')->default('AVAILABLE'); // AVAILABLE, IN_USE, MAINTENANCE
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};