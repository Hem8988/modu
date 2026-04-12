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
        if (Schema::hasTable('leads')) return;
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enquiry_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->string('city')->nullable();
            $table->string('budget')->nullable();
            $table->text('address')->nullable();
            $table->string('shades_needed')->nullable();
            $table->text('feedback')->nullable();
            $table->integer('windows_count')->default(0);
            $table->string('timeline')->nullable();
            $table->enum('status', ['new', 'contacted', 'qualified', 'lost', 'appointment', 'converted', 'won', 'project'])->default('new');
            $table->integer('lead_score')->default(0);
            $table->dateTime('appointment_date')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('service')->nullable();
            $table->text('deal_details')->nullable();
            $table->date('install_date')->nullable();
            $table->decimal('advance_amount', 10, 2)->nullable();
            $table->string('payment_status')->nullable();
            $table->string('invoice_number')->nullable();
            $table->text('lost_reason')->nullable();
            $table->string('source')->nullable();
            $table->dateTime('next_follow_up')->nullable();
            $table->text('reminder_note')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->timestamps();
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
