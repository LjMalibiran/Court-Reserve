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
        Schema::create('rental_items', function (Blueprint $table) {
            $table->id();
            
            // Relational Integrity: Links the rented item to a specific reservation
            $table->foreignId('reservation_id')->constrained()->cascadeOnDelete();
            
            // Item Details
            $table->string('item_name'); // e.g., 'Racket', 'Shuttlecock'
            $table->integer('quantity');
            $table->decimal('price_per_item', 8, 2); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_items');
    }
};