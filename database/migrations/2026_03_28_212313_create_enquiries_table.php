<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (Schema::hasTable('enquiries')) return;
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('project')->nullable();
            $table->string('budget')->nullable();
            $table->text('message')->nullable();
            $table->string('source')->default('Landing Page');
            $table->enum('status', ['pending', 'converted', 'spam'])->default('pending');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('enquiries'); }
};
