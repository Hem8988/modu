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
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('lead_id');
            $table->dateTime('date');
            $table->string('type')->default('call');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
        });

        Schema::create('installations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('lead_id');
            $table->date('date');
            $table->string('team')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
        Schema::dropIfExists('installations');
    }
};
