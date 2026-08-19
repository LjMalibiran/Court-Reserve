<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            
            // Added for QR Verification feature (e.g., "BC26-01")
            $table->string('reservation_code')->unique(); 
            
            // Relational Integrity: Links to the users table 
            // Made nullable() so unregistered Walk-In customers can still book a court
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('walk_in_name')->nullable(); // Captures the name of unregistered walk-ins
            
            // Relational Integrity: Links to the courts table
            $table->foreignId('court_id')->constrained()->onDelete('cascade');
            
            // Date & Time Tracking
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            
            // Payment tracking for the 50% down payment rule
            $table->decimal('total_price', 8, 2);
            $table->decimal('amount_paid', 8, 2)->default(0.00);
            $table->string('payment_reference')->nullable(); // Tracks GCash reference numbers
            
            // Added 'in-play' status to match your Walk-In UI tabs
            $table->enum('status', ['pending', 'confirmed', 'in-play', 'completed', 'cancelled'])->default('pending');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};