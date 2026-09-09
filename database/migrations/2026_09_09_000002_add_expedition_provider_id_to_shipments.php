<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * SAFE ADDITIVE MIGRATION:
     * Add expedition_provider_id FK to shipments.
     * The old 'carrier' text column is kept for backward compatibility with existing data.
     * New shipments will use expedition_provider_id; old ones fall back to carrier text.
     */
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            // Add after 'carrier' column — nullable so existing rows aren't affected
            $table->foreignId('expedition_provider_id')
                  ->nullable()
                  ->after('carrier')
                  ->constrained('expedition_providers')
                  ->onDelete('set null');

            $table->index('expedition_provider_id');
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropForeign(['expedition_provider_id']);
            $table->dropIndex(['expedition_provider_id']);
            $table->dropColumn('expedition_provider_id');
        });
    }
};
