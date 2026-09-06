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
        Schema::table('shipments', function (Blueprint $table) {
            $table->string('shipping_type')->default('INTERNAL')->after('shipment_number');
            $table->string('carrier')->nullable()->after('shipping_type');
            $table->string('tracking_number')->nullable()->after('carrier');

            $table->index('shipping_type');
            $table->index('tracking_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropIndex(['shipping_type']);
            $table->dropIndex(['tracking_number']);
            $table->dropColumn(['shipping_type', 'carrier', 'tracking_number']);
        });
    }
};
