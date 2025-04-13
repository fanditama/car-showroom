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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('selected_bank')->nullable()->after('payment_method');
            $table->string('payment_token')->nullable()->after('selected_bank');
            $table->string('payment_url')->nullable()->after('payment_token');
            $table->string('order_id')->unique()->nullable()->after('payment_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['selected_bank', 'payment_token', 'payment_url', 'order_id']);
        });
    }
};
