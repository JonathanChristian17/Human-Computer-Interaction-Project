<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->integer('price_per_night')->change();
        });
    }

    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->decimal('price_per_night', 10, 2)->change();
        });
    }
}; 