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
        Schema::table('quotes', function (Blueprint $table) {
            $table->date('bill_date')->nullable()->after('quote_number');
            $table->string('party_bill_no')->nullable()->after('bill_date');
            $table->decimal('subtotal', 15, 2)->default(0)->after('party_bill_no');
            $table->decimal('vat_amount', 15, 2)->default(0)->after('subtotal');
            $table->decimal('freight', 15, 2)->default(0)->after('vat_amount');
            $table->decimal('discount', 15, 2)->default(0)->after('freight');
            $table->text('narration')->nullable()->after('status');
            $table->text('comments')->nullable()->after('narration');
        });

        Schema::table('quote_items', function (Blueprint $table) {
            $table->decimal('vat_percentage', 10, 2)->default(0)->after('unit_price');
            $table->decimal('vat_amount', 15, 2)->default(0)->after('vat_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn(['bill_date', 'party_bill_no', 'subtotal', 'vat_amount', 'freight', 'discount', 'narration', 'comments']);
        });

        Schema::table('quote_items', function (Blueprint $table) {
            $table->dropColumn(['vat_percentage', 'vat_amount']);
        });
    }
};
