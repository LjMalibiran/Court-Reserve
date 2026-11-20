<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'refund_gcash_name')) {
                $table->string('refund_gcash_name')->nullable()->after('refund_amount');
            }
            if (!Schema::hasColumn('reservations', 'refund_gcash_number')) {
                $table->string('refund_gcash_number', 20)->nullable()->after('refund_gcash_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $cols = array_filter(['refund_gcash_name', 'refund_gcash_number'], fn ($c) => Schema::hasColumn('reservations', $c));
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
