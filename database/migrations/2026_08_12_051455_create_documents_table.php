<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('tracking_update_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type');
            $table->integer('file_size'); // Bytes
            $table->string('type')->default('OTHER'); // PHOTO, PDF, DOC, OTHER
            
            $table->timestamps();
            
            $table->index('shipment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};