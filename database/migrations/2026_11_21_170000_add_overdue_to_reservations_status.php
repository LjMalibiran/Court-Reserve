<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `reservations` MODIFY COLUMN `status` ENUM('pending', 'confirmed', 'in-play', 'completed', 'cancelled', 'overdue') NOT NULL DEFAULT 'pending'");
        } else {
            Schema::table('reservations', function (Blueprint $table) {
                $table->string('status', 30)->default('pending')->change();
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `reservations` MODIFY COLUMN `status` ENUM('pending', 'confirmed', 'in-play', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
        }
    }
};
