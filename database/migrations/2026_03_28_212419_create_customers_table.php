<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (Schema::hasTable('customers')) return;
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('project')->nullable();
            $table->string('source')->nullable();
            $table->date('converted_date')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('customers'); }
};
