<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('restrict'); // Jangan hapus customer jika ada order
            $table->date('order_date');
            $table->string('status')->default('PENDING'); // PENDING, PROCESSING, COMPLETED, CANCELLED
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('order_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};