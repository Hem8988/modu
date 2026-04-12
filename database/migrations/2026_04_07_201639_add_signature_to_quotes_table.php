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
            // Check if column exists before adding or renaming
            if (Schema::hasColumn('quotes', 'token')) {
                $table->renameColumn('token', 'client_token');
            }
            if (!Schema::hasColumn('quotes', 'signature_data')) {
                $table->longText('signature_data')->nullable(); // For digital drawing
            }
            if (!Schema::hasColumn('quotes', 'signed_at')) {
                $table->timestamp('signed_at')->nullable();
            }
            if (!Schema::hasColumn('quotes', 'signature_name')) {
                $table->string('signature_name')->nullable();
            }
            if (!Schema::hasColumn('quotes', 'signature_ip')) {
                $table->string('signature_ip')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            // Avoid errors on rollback if names were already correct
            if (Schema::hasColumn('quotes', 'client_token')) {
                $table->renameColumn('client_token', 'token');
            }
            $table->dropColumn(['signature_data']);
        });
    }
};
