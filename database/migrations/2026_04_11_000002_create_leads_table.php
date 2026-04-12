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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_submission_id')->nullable();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('postal_code');
            $table->string('blinds_type')->nullable();
            $table->string('num_windows')->nullable();
            $table->string('project_timeline')->nullable();
            $table->text('special_message')->nullable();
            $table->enum('status', ['new', 'contacted', 'qualified', 'proposal_sent', 'won', 'lost'])->default('new');
            $table->text('notes')->nullable();
            $table->timestamp('last_contact_date')->nullable();
            $table->timestamps();

            // Foreign key relationship to form_submissions
            $table->foreign('form_submission_id')->references('id')->on('form_submissions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
