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
            // Add driver relationship
            $table->foreignId('driver_id')->nullable()->after('car_id')->constrained('drivers')->nullOnDelete();
            
            // Add cancellation fields
            $table->text('cancellation_reason')->nullable()->after('admin_notes');
            $table->timestamp('cancellation_requested_at')->nullable()->after('cancellation_reason');
            $table->decimal('cancellation_refund_percent', 5, 2)->default(0)->after('cancellation_requested_at');
            $table->decimal('refund_amount', 10, 2)->default(0)->after('cancellation_refund_percent');
        });

        // Update status enum to include pending and approved
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('pending','approved','reserved','ongoing','completed','cancelled','cancellation_requested') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropForeignKey(['driver_id']);
            $table->dropColumn([
                'driver_id',
                'cancellation_reason',
                'cancellation_requested_at',
                'cancellation_refund_percent',
                'refund_amount',
            ]);
        });

        // Revert status enum
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('reserved','ongoing','completed','cancelled') NOT NULL DEFAULT 'reserved'");
    }
};
