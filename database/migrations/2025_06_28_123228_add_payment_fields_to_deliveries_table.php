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
        Schema::table('deliveries', function (Blueprint $table) {
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('weight');
            $table->string('payment_status')->default('pending')->after('shipping_cost'); // pending, paid, failed
            $table->string('payment_token')->nullable()->after('payment_status');
            $table->string('payment_type')->nullable()->after('payment_token');
            $table->timestamp('paid_at')->nullable()->after('payment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn(['shipping_cost', 'payment_status', 'payment_token', 'payment_type', 'paid_at']);
        });
    }
};
