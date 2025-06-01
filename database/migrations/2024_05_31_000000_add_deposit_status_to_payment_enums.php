<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add 'deposit' to bookings payment_status enum
        DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status ENUM('pending', 'paid', 'cancelled', 'refunded', 'deposit') DEFAULT 'pending'");
        
        // Add deposit_amount column to bookings table
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('deposit_amount', 10, 2)->default(0)->after('deposit');
        });
    }

    public function down()
    {
        // Remove 'deposit' from bookings payment_status enum
        DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status ENUM('pending', 'paid', 'cancelled', 'refunded') DEFAULT 'pending'");
        
        // Remove deposit_amount column from bookings table
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('deposit_amount');
        });
    }
}; 