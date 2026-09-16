<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('cancellation_reason')->nullable()->after('payment_type');
            $table->timestamp('cancelled_at')->nullable()->after('cancellation_reason');
            $table->string('refund_status')->nullable()->after('cancelled_at'); // pending, refunded, rejected
            $table->decimal('refund_amount', 10, 2)->nullable()->after('refund_status');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['cancellation_reason', 'cancelled_at', 'refund_status', 'refund_amount']);
        });
    }
};
