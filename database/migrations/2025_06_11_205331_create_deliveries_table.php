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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('sender_name');
            $table->string('receiver_name');
            $table->foreignId('from_city_id');
            $table->foreignId('ship_id');
            $table->foreignId('to_city_id');
            $table->date('delivery_date');
            $table->string('item_name');
            $table->decimal('weight', 8, 2);
            $table->string('resi')->unique();
            $table->foreignId('user_id')->nullable();
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->string('payment_status')->default('pending');
            $table->string('payment_token')->nullable();
            $table->string('payment_type')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
