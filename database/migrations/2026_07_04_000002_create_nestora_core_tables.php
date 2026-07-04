<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('address')->nullable();
            $table->unsignedInteger('floors')->default(1);
            $table->unsignedInteger('total_flats')->default(0);
            $table->timestamps();
        });

        Schema::create('flats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->constrained()->cascadeOnDelete();
            $table->string('flat_number');
            $table->unsignedInteger('floor')->nullable();
            $table->string('block')->nullable();
            $table->string('type')->nullable();
            $table->unsignedInteger('bedrooms')->default(0);
            $table->decimal('area_sqft', 8, 2)->nullable();
            $table->string('status')->default('vacant');
            $table->timestamps();

            $table->unique(['building_id', 'flat_number']);
            $table->index(['status', 'floor']);
        });

        Schema::create('resident_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('flat_id')->nullable()->constrained()->nullOnDelete();
            $table->string('resident_type');
            $table->date('move_in_date')->nullable();
            $table->date('move_out_date')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();

            $table->index(['flat_id', 'resident_type']);
        });

        Schema::create('flat_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_profile_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('relationship')->nullable();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicle_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_profile_id')->constrained()->cascadeOnDelete();
            $table->string('vehicle_type')->default('car');
            $table->string('registration_number')->unique();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('parking_slot')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('flat_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('type')->default('other');
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('status')->default('pending_verification');
            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable()->index();
            $table->timestamps();

            $table->index(['user_id', 'type', 'status']);
        });

        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('staff_type');
            $table->string('employee_code')->unique();
            $table->string('shift')->nullable();
            $table->date('joined_at')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('flat_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('category')->nullable();
            $table->text('description');
            $table->string('priority')->default('medium');
            $table->string('status')->default('open');
            $table->timestamps();

            $table->index(['resident_id', 'status']);
        });

        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('instructions')->nullable();
            $table->string('priority')->default('medium');
            $table->string('status')->default('todo');
            $table->timestamp('due_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['assigned_to', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_orders');
        Schema::dropIfExists('complaints');
        Schema::dropIfExists('staff_profiles');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('vehicle_registrations');
        Schema::dropIfExists('flat_members');
        Schema::dropIfExists('resident_profiles');
        Schema::dropIfExists('flats');
        Schema::dropIfExists('buildings');
    }
};
