<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('guests');
            
            // Kolom tambahan
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('id_number');
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('pending');
            $table->text('billing_address');
            $table->string('billing_city');
            $table->string('billing_province');
            $table->string('billing_postal_code');
            $table->text('special_requests')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
