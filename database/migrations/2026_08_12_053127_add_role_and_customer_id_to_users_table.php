<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom role setelah email
            $table->string('role')->default('CUSTOMER')->after('email');
            
            // Tambahkan customer_id, nullable (karena admin tidak punya customer), restrict on delete
            $table->foreignId('customer_id')
                  ->nullable()
                  ->after('role')
                  ->constrained('customers')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn(['role', 'customer_id']);
        });
    }
};