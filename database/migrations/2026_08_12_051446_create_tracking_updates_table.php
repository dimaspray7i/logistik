<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->onDelete('cascade');
            $table->foreignId('route_point_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->constrained()->onDelete('restrict'); // User yang input
            $table->string('status'); // DEPARTED, TRANSIT, DELIVERED, etc
            $table->string('location');
            $table->text('description');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->dateTime('tracked_at');
            $table->timestamps();
            
            $table->index('shipment_id');
            $table->index('tracked_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_updates');
    }
};