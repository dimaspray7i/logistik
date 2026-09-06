<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('shipment_code_prefix')->nullable()->unique()->after('company_name');
            $table->index('shipment_code_prefix');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex(['shipment_code_prefix']);
            $table->dropUnique(['shipment_code_prefix']);
            $table->dropColumn('shipment_code_prefix');
        });
    }
};
