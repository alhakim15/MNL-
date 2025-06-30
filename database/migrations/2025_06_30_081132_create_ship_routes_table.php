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
        Schema::create('ship_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ship_id')->constrained()->onDelete('cascade');
            $table->foreignId('origin_city_id')->constrained('cities')->onDelete('cascade');
            $table->foreignId('destination_city_id')->constrained('cities')->onDelete('cascade');
            $table->decimal('distance_km', 8, 2)->nullable()->comment('Distance in kilometers');
            $table->integer('estimated_hours')->nullable()->comment('Estimated travel time in hours');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Ensure unique route per ship
            $table->unique(['ship_id', 'origin_city_id', 'destination_city_id']);

            // Add indexes for better performance
            $table->index(['origin_city_id', 'destination_city_id']);
            $table->index(['ship_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ship_routes');
    }
};
