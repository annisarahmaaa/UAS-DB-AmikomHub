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
            $table->string('coupon_code')->nullable()->after('status');
            $table->integer('discount_amount')->default(0)->after('coupon_code');
            $table->string('ticket_tier_name')->nullable()->after('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['coupon_code', 'discount_amount', 'ticket_tier_name']);
        });
    }
};
