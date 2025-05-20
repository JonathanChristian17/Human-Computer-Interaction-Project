<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('managed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('check_in_time')->nullable();
            $table->timestamp('check_out_time')->nullable();
            // Rename check_in and check_out columns to be more specific
            $table->renameColumn('check_in', 'check_in_date');
            $table->renameColumn('check_out', 'check_out_date');
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['managed_by']);
            $table->dropColumn(['managed_by', 'check_in_time', 'check_out_time']);
            $table->renameColumn('check_in_date', 'check_in');
            $table->renameColumn('check_out_date', 'check_out');
        });
    }
}; 