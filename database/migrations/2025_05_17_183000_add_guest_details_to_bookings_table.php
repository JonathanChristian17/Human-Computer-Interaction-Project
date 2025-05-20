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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('full_name')->after('room_id');
            $table->string('email')->after('full_name');
            $table->string('phone')->after('email');
            $table->string('id_number')->after('phone');
            $table->integer('guests')->after('id_number');
            $table->string('billing_address')->after('guests');
            $table->string('billing_city')->after('billing_address');
            $table->string('billing_province')->after('billing_city');
            $table->string('billing_postal_code')->after('billing_province');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'full_name',
                'email',
                'phone',
                'id_number',
                'guests',
                'billing_address',
                'billing_city',
                'billing_province',
                'billing_postal_code'
            ]);
        });
    }
}; 