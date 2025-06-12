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
            $table->string('sender_name');        // Nama Pengirim
            $table->string('receiver_name');      // Nama Penerima
            $table->foreignId('from_city_id');
            $table->foreignId('ship_id');         // ID kapal
            $table->foreignId('to_city_id');
            $table->date('delivery_date');        // Tanggal pengiriman
            $table->string('item_name');          // Nama barang
            $table->decimal('weight', 8, 2);      // Berat barang (dalam ton)
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
