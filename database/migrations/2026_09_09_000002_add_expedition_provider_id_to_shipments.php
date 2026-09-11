<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add expedition_provider_id FK to shipments.
     *
     * The column was created manually in the database before this migration
     * was recorded, so we guard against a duplicate-column error by checking
     * first. The foreign key and index are added regardless, using the same
     * guard pattern to stay safe across all environments (dev, staging, prod).
     *
     * Old 'carrier' text column is kept for backward compatibility with
     * existing data. New shipments use expedition_provider_id; old ones fall
     * back to the carrier text field.
     */
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            // Guard: only add the column if it does not already exist.
            // (In some environments it was created manually before this
            //  migration ran.)
            if (!Schema::hasColumn('shipments', 'expedition_provider_id')) {
                $table->foreignId('expedition_provider_id')
                      ->nullable()
                      ->after('carrier')
                      ->constrained('expedition_providers')
                      ->onDelete('set null');

                $table->index('expedition_provider_id');
            } else {
                // Column already exists — just ensure the FK constraint and
                // index are present (they were not created when the column was
                // added manually).

                // Check FK existence before adding it.
                $hasFk = DB::select("
                    SELECT CONSTRAINT_NAME
                    FROM information_schema.TABLE_CONSTRAINTS
                    WHERE TABLE_SCHEMA = DATABASE()
                      AND TABLE_NAME = 'shipments'
                      AND CONSTRAINT_NAME = 'shipments_expedition_provider_id_foreign'
                      AND CONSTRAINT_TYPE = 'FOREIGN KEY'
                ");

                if (empty($hasFk)) {
                    $table->foreign('expedition_provider_id')
                          ->references('id')->on('expedition_providers')
                          ->onDelete('set null');
                }

                // Check index existence before adding it.
                $hasIndex = DB::select("
                    SELECT INDEX_NAME
                    FROM information_schema.STATISTICS
                    WHERE TABLE_SCHEMA = DATABASE()
                      AND TABLE_NAME = 'shipments'
                      AND INDEX_NAME = 'shipments_expedition_provider_id_index'
                ");

                if (empty($hasIndex)) {
                    $table->index('expedition_provider_id');
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            // Drop FK if it exists
            $hasFk = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.TABLE_CONSTRAINTS
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'shipments'
                  AND CONSTRAINT_NAME = 'shipments_expedition_provider_id_foreign'
                  AND CONSTRAINT_TYPE = 'FOREIGN KEY'
            ");
            if (!empty($hasFk)) {
                $table->dropForeign(['expedition_provider_id']);
            }

            // Drop index if it exists
            $hasIndex = DB::select("
                SELECT INDEX_NAME
                FROM information_schema.STATISTICS
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'shipments'
                  AND INDEX_NAME = 'shipments_expedition_provider_id_index'
            ");
            if (!empty($hasIndex)) {
                $table->dropIndex(['expedition_provider_id']);
            }

            // Drop column if it exists
            if (Schema::hasColumn('shipments', 'expedition_provider_id')) {
                $table->dropColumn('expedition_provider_id');
            }
        });
    }
};

