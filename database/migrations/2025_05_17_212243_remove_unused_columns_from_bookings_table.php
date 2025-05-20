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
            // Drop unused columns
            $table->dropColumn([
                'guests',
                'billing_address',
                'billing_city',
                'billing_province',
                'billing_postal_code'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Re-add columns if needed to rollback
            $table->integer('guests')->after('id_number');
            $table->string('billing_address')->after('guests');
            $table->string('billing_city')->after('billing_address');
            $table->string('billing_province')->after('billing_city');
            $table->string('billing_postal_code')->after('billing_province');
        });
    }
};
