<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (Schema::hasTable('complaints')) return;
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['open', 'progress', 'resolved'])->default('open');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('complaints'); }
};
