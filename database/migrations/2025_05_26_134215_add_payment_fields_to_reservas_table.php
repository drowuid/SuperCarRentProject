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
        Schema::table('reservas', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('email'); // e.g., 'paypal' or 'atm'
            $table->string('payment_status')->default('pending')->after('payment_method'); // 'pending', 'paid'
            $table->string('atm_reference')->nullable()->after('payment_status'); // if 'atm' method is used
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->dropColumn('payment_status');
            $table->dropColumn('atm_reference');
        });
    }
};
