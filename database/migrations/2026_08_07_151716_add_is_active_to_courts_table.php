<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('courts', function (Blueprint $table) {
            // Adds a boolean (true/false) column defaulting to true
            $table->boolean('is_active')->default(true);
        });
    }

    public function down()
    {
        Schema::table('courts', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};