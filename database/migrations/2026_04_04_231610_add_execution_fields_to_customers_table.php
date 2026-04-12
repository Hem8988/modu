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
        Schema::table('customers', function (Blueprint $table) {
            $table->text('measurement_details')->nullable()->after('address');
            $table->date('installation_date')->nullable()->after('measurement_details');
            $table->string('payment_status')->default('pending')->after('installation_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['measurement_details', 'installation_date', 'payment_status']);
        });
    }
};
