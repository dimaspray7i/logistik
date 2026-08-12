<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_number')->unique();
            $table->foreignId('order_id')->constrained()->onDelete('restrict');
            $table->foreignId('customer_id')->constrained()->onDelete('restrict');
            $table->foreignId('vehicle_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('driver_id')->nullable()->constrained()->onDelete('set null');
            
            $table->string('origin');
            $table->string('destination');
            $table->dateTime('departure_date')->nullable();
            $table->dateTime('estimated_arrival')->nullable();
            $table->dateTime('actual_arrival')->nullable();
            
            $table->decimal('total_weight', 10, 2)->default(0);
            $table->string('status')->default('DRAFT'); // DRAFT, READY, IN_TRANSIT, ARRIVED, DELIVERED, DELAYED, CANCELLED
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            $table->index('status');
            $table->index('shipment_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};