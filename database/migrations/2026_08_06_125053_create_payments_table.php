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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            
            // Relational Integrity: Links to the specific reservation
            $table->foreignId('reservation_id')->constrained()->cascadeOnDelete();
            
            // Core Payment Details
            $table->enum('payment_method', ['gcash', 'cash']);
            $table->decimal('amount', 8, 2); // The specific amount paid in this transaction
            
            // GCash Specifics (Nullable for Cash payments)
            $table->string('reference_number')->nullable();
            $table->string('receipt_image')->nullable(); // File path for the uploaded screenshot
            
            // Refund Tracking for the 5-Hour Policy
            $table->boolean('is_refunded')->default(false);
            $table->decimal('refund_amount', 8, 2)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};