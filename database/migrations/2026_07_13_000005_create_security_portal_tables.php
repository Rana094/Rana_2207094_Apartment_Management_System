<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_request_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('flat_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('security_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('visitor_name');
            $table->string('visitor_phone')->nullable();
            $table->string('access_code')->nullable()->index();
            $table->string('event_type');
            $table->string('purpose')->nullable();
            $table->string('vehicle_plate')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();

            $table->index(['event_type', 'occurred_at']);
        });

        Schema::create('security_incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('flat_id')->nullable()->constrained()->nullOnDelete();
            $table->string('subject');
            $table->string('category')->default('general');
            $table->text('description');
            $table->string('status')->default('open');
            $table->timestamp('occurred_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['category', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_incidents');
        Schema::dropIfExists('visitor_logs');
    }
};
