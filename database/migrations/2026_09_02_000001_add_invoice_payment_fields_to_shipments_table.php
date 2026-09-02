<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->string('invoice_payment_status')->default('Belum Dibayar')->after('status');
            $table->date('invoice_payment_date')->nullable()->after('invoice_payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn(['invoice_payment_status', 'invoice_payment_date']);
        });
    }
};
