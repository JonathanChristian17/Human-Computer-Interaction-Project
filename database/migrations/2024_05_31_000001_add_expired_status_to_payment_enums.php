<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, create temporary columns
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_status_temp')->default('pending');
            $table->string('status_temp')->default('pending');
        });

        // Copy data to temporary columns
        DB::statement('UPDATE bookings SET payment_status_temp = payment_status, status_temp = status');

        // Drop the original enum columns
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('payment_status');
            $table->dropColumn('status');
        });

        // Create new enum columns with updated values
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('payment_status', ['pending', 'paid', 'cancelled', 'refunded', 'deposit', 'expired'])->default('pending');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'checked_in', 'checked_out', 'expired'])->default('pending');
        });

        // Copy data back from temporary columns
        DB::statement('UPDATE bookings SET payment_status = payment_status_temp, status = status_temp');

        // Update expired transactions
        DB::statement("
            UPDATE bookings b
            JOIN transactions t ON b.id = t.booking_id
            SET b.payment_status = 'expired',
                b.status = 'expired'
            WHERE t.transaction_status = 'expire'
        ");

        // Drop temporary columns
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('payment_status_temp');
            $table->dropColumn('status_temp');
        });
    }

    public function down()
    {
        // First, create temporary columns
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_status_temp')->default('pending');
            $table->string('status_temp')->default('pending');
        });

        // Copy data to temporary columns, converting 'expired' to 'cancelled'
        DB::statement("
            UPDATE bookings 
            SET payment_status_temp = CASE WHEN payment_status = 'expired' THEN 'cancelled' ELSE payment_status END,
                status_temp = CASE WHEN status = 'expired' THEN 'cancelled' ELSE status END
        ");

        // Drop the current enum columns
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('payment_status');
            $table->dropColumn('status');
        });

        // Create new enum columns with original values
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('payment_status', ['pending', 'paid', 'cancelled', 'refunded', 'deposit'])->default('pending');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'checked_in', 'checked_out'])->default('pending');
        });

        // Copy data back from temporary columns
        DB::statement('UPDATE bookings SET payment_status = payment_status_temp, status = status_temp');

        // Drop temporary columns
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('payment_status_temp');
            $table->dropColumn('status_temp');
        });
    }
}; 