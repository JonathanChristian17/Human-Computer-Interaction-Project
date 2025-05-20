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
            // Drop the old columns if they exist
            if (Schema::hasColumn('bookings', 'check_in')) {
                $table->dropColumn('check_in');
            }
            if (Schema::hasColumn('bookings', 'check_out')) {
                $table->dropColumn('check_out');
            }

            // Add the new columns if they don't exist
            if (!Schema::hasColumn('bookings', 'check_in_date')) {
                $table->date('check_in_date')->after('id_number');
            }
            if (!Schema::hasColumn('bookings', 'check_out_date')) {
                $table->date('check_out_date')->after('check_in_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'check_in_date')) {
                $table->dropColumn('check_in_date');
            }
            if (Schema::hasColumn('bookings', 'check_out_date')) {
                $table->dropColumn('check_out_date');
            }

            if (!Schema::hasColumn('bookings', 'check_in')) {
                $table->date('check_in');
            }
            if (!Schema::hasColumn('bookings', 'check_out')) {
                $table->date('check_out');
            }
        });
    }
}; 