<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('order_id')->unique();
            $table->decimal('gross_amount', 10, 2);
            $table->string('payment_type')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('transaction_status')->default('pending');
            $table->string('payment_status')->default('pending');
            $table->string('transaction_time')->nullable();
            $table->string('payment_code')->nullable(); // For VA numbers
            $table->string('pdf_url')->nullable();
            $table->json('raw_response')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}; 