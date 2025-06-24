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
        Schema::create('delivery_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained()->onDelete('cascade');
            $table->string('status'); // contoh: "Di pelabuhan asal", "Dalam perjalanan", "Tiba tujuan"
            $table->string('location')->nullable(); // pelabuhan mana
            $table->text('note')->nullable(); // catatan tambahan (opsional)
            $table->timestamp('logged_at')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_status_logs');
    }
};
