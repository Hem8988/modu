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
        // 1. Expand Complaints Registry
        Schema::table('complaints', function (Blueprint $table) {
            if (!Schema::hasColumn('complaints', 'priority')) {
                $table->string('priority')->default('medium')->after('title');
            }
            if (!Schema::hasColumn('complaints', 'assigned_staff_id')) {
                $table->unsignedBigInteger('assigned_staff_id')->nullable()->after('priority');
            }
        });

        // 2. Customer Feedback Hub
        if (!Schema::hasTable('feedbacks')) {
            Schema::create('feedbacks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('customer_id')->nullable();
                $table->string('name')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('project_id')->nullable();
                $table->string('type')->default('General Feedback');
                $table->integer('rating')->default(5);
                $table->text('comments')->nullable();
                $table->timestamps();
            });
        }

        // 3. Service Requests Registry
        if (!Schema::hasTable('service_requests')) {
            Schema::create('service_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('customer_id')->nullable();
                $table->string('product_type')->nullable();
                $table->text('issue_description');
                $table->date('requested_date');
                $table->string('assigned_technician')->nullable();
                $table->string('status')->default('pending');
                $table->timestamps();
            });
        }

        // 4. Resolution Forensics Hub
        if (!Schema::hasTable('resolutions')) {
            Schema::create('resolutions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('complaint_id')->nullable();
                $table->unsignedBigInteger('staff_id')->nullable();
                $table->string('action_taken');
                $table->text('notes')->nullable();
                $table->date('resolution_date');
                $table->string('status')->default('resolved');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('resolutions');
        Schema::dropIfExists('service_requests');
        Schema::dropIfExists('feedbacks');
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn(['priority', 'assigned_staff_id']);
        });
    }
};
