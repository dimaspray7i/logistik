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
        if (Schema::hasTable('tracking_updates')) {
            Schema::table('tracking_updates', function (Blueprint $table) {
                if (!Schema::hasColumn('tracking_updates', 'address')) {
                    $table->string('address', 500)->nullable()->after('location');
                }
                if (!Schema::hasColumn('tracking_updates', 'city')) {
                    $table->string('city', 100)->nullable();
                }
                if (!Schema::hasColumn('tracking_updates', 'province')) {
                    $table->string('province', 100)->nullable();
                }
                if (!Schema::hasColumn('tracking_updates', 'country')) {
                    $table->string('country', 100)->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tracking_updates')) {
            Schema::table('tracking_updates', function (Blueprint $table) {
                $columns = array_filter(['address', 'city', 'province', 'country'], fn($col) => Schema::hasColumn('tracking_updates', $col));
                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};
