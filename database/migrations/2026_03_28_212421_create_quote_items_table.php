<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (Schema::hasTable('quote_items')) return;
        Schema::create('quote_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quote_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name');
            $table->decimal('width', 8, 2)->default(0);
            $table->decimal('height', 8, 2)->default(0);
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->json('options_json')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('quote_items'); }
};
