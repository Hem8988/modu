<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (Schema::hasTable('quotes')) return;
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id');
            $table->string('quote_number')->unique();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['draft', 'sent', 'accepted', 'rejected'])->default('draft');
            $table->string('client_token')->nullable()->unique();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('quotes'); }
};
