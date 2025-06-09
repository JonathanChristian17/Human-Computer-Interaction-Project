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
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('price_per_night');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->bigInteger('price_per_night')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('price_per_night');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->decimal('price_per_night', 10, 2)->default(0);
        });
    }
};
