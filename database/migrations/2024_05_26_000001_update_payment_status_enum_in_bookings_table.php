<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, modify the enum to include 'cancelled'
        DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status ENUM('pending', 'paid', 'cancelled', 'refunded') DEFAULT 'pending'");
        
        // Update any existing 'failed' status to 'cancelled'
        DB::statement("UPDATE bookings SET payment_status = 'cancelled' WHERE payment_status = 'failed'");
    }

    public function down()
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status ENUM('pending', 'paid', 'refunded') DEFAULT 'pending'");
        
        // Update any 'cancelled' status back to 'pending'
        DB::statement("UPDATE bookings SET payment_status = 'pending' WHERE payment_status = 'cancelled'");
    }
}; 