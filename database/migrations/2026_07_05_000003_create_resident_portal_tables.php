<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('flat_id')->nullable()->constrained()->nullOnDelete();
            $table->string('bill_number')->unique();
            $table->date('billing_month');
            $table->string('type')->default('monthly_service_charge');
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->string('status')->default('unpaid');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['resident_id', 'status', 'due_date']);
        });

        Schema::create('payment_proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('transaction_reference')->nullable();
            $table->string('file_path');
            $table->string('status')->default('pending');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable()->index();
            $table->timestamps();

            $table->index(['bill_id', 'status']);
        });

        Schema::create('visitor_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('flat_id')->nullable()->constrained()->nullOnDelete();
            $table->string('visitor_name');
            $table->string('visitor_phone')->nullable();
            $table->string('purpose')->nullable();
            $table->date('visit_date');
            $table->time('expected_entry_time')->nullable();
            $table->string('access_code')->unique();
            $table->string('status')->default('pending');
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('checked_out_at')->nullable();
            $table->timestamps();

            $table->index(['resident_id', 'status', 'visit_date']);
        });

        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->decimal('booking_fee', 10, 2)->default(0);
            $table->string('status')->default('available');
            $table->timestamps();
        });

        Schema::create('facility_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('facility_id')->constrained()->cascadeOnDelete();
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('purpose')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->index(['resident_id', 'status', 'booking_date']);
        });

        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });

        Schema::create('poll_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->timestamps();
        });

        Schema::create('poll_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $table->foreignId('poll_option_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['poll_id', 'user_id']);
        });

        Schema::create('emergency_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('flat_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type')->default('general');
            $table->text('message')->nullable();
            $table->string('status')->default('open');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['resident_id', 'status']);
        });

        Schema::create('move_out_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('flat_id')->nullable()->constrained()->nullOnDelete();
            $table->date('requested_move_out_date');
            $table->text('reason')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->index(['resident_id', 'status']);
        });

        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('body');
            $table->string('audience')->default('all');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['audience', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notices');
        Schema::dropIfExists('move_out_requests');
        Schema::dropIfExists('emergency_requests');
        Schema::dropIfExists('poll_votes');
        Schema::dropIfExists('poll_options');
        Schema::dropIfExists('polls');
        Schema::dropIfExists('facility_bookings');
        Schema::dropIfExists('facilities');
        Schema::dropIfExists('visitor_requests');
        Schema::dropIfExists('payment_proofs');
        Schema::dropIfExists('bills');
    }
};
