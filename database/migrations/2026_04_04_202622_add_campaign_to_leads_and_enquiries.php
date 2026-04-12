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
        Schema::table('leads', function (Blueprint $table) {
            $table->string('campaign')->nullable()->after('source');
        });
        Schema::table('enquiries', function (Blueprint $table) {
            $table->string('campaign')->nullable()->after('source');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('campaign');
        });
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropColumn('campaign');
        });
    }
};
