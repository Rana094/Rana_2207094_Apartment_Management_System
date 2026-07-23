<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Stores the flat selected during signup before manager approval creates ResidentProfile.
            $table->foreignId('requested_flat_id')
                ->nullable()
                ->after('flat_info')
                ->constrained('flats')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rollback removes both the column and its foreign-key constraint.
            $table->dropConstrainedForeignId('requested_flat_id');
        });
    }
};
