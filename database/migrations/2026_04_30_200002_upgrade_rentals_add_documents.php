<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            // Document paths
            $table->string('contract_file_path')->nullable()->after('contract_signed_at');
            $table->string('id_file_path')->nullable()->after('contract_file_path');
            
            // Document status tracking
            $table->enum('contract_status', ['pending', 'uploaded', 'verified', 'rejected'])
                  ->default('pending')
                  ->after('id_file_path');
            $table->enum('id_status', ['pending', 'uploaded', 'verified', 'rejected'])
                  ->default('pending')
                  ->after('contract_status');
            
            // Verification timestamps
            $table->timestamp('contract_verified_at')->nullable()->after('id_status');
            $table->foreignId('contract_verified_by')->nullable()->after('contract_verified_at')->constrained('users')->nullOnDelete();
            $table->timestamp('id_verified_at')->nullable()->after('contract_verified_by');
            $table->foreignId('id_verified_by')->nullable()->after('id_verified_at')->constrained('users')->nullOnDelete();
            
            // Return fields
            $table->timestamp('vehicle_released_at')->nullable()->after('id_verified_by');
            $table->foreignId('released_by')->nullable()->after('vehicle_released_at')->constrained('users')->nullOnDelete();
        });

        // Update status enum to include new statuses
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('pending','approved','documents_pending','documents_verified','reserved','ongoing','return_pending','completed','cancelled','cancellation_requested') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropForeign(['contract_verified_by']);
            $table->dropForeign(['id_verified_by']);
            $table->dropForeign(['released_by']);
            
            $table->dropColumn([
                'contract_file_path',
                'id_file_path',
                'contract_status',
                'id_status',
                'contract_verified_at',
                'contract_verified_by',
                'id_verified_at',
                'id_verified_by',
                'vehicle_released_at',
                'released_by',
            ]);
        });

        // Revert status enum
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('pending','approved','reserved','ongoing','completed','cancelled','cancellation_requested') NOT NULL DEFAULT 'pending'");
    }
};
