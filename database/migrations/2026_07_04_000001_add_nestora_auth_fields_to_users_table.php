<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('role')->default('resident')->after('password');
            $table->string('status')->default('pending_verification')->after('role');
            $table->string('resident_type')->nullable()->after('status');
            $table->string('flat_info')->nullable()->after('resident_type');
            $table->string('document_path')->nullable()->after('flat_info');
            $table->timestamp('approved_at')->nullable()->after('email_verified_at');
            $table->unsignedBigInteger('approved_by')->nullable()->index()->after('approved_at');
            $table->text('rejection_reason')->nullable()->after('approved_by');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'role',
                'status',
                'resident_type',
                'flat_info',
                'document_path',
                'approved_at',
                'approved_by',
                'rejection_reason',
            ]);
        });
    }
};
