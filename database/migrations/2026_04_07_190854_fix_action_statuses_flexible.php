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
        Schema::table('follow_ups', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->string('status')->default('scheduled')->change();
        });

        Schema::table('installations', function (Blueprint $table) {
            $table->string('status')->default('scheduled')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('follow_ups', function (Blueprint $table) {
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending')->change();
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled')->change();
        });

        Schema::table('installations', function (Blueprint $table) {
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled')->change();
        });
    }
};
