<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->foreignId('customer_user_id')->nullable()->after('car_id')
                  ->constrained('users')->nullOnDelete();

            $table->enum('rental_type', ['with_driver', 'self_drive'])
                  ->default('self_drive')->after('customer_name');

            $table->string('destination')->nullable()->after('rental_type');
            $table->unsignedInteger('distance_km')->default(0)->after('destination');
            $table->decimal('distance_surcharge', 10, 2)->default(0)->after('distance_km');

            $table->string('driver_license_path')->nullable()->after('distance_surcharge');

            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])
                  ->default('unpaid')->after('total_cost');
            $table->decimal('amount_paid', 10, 2)->default(0)->after('payment_status');

            $table->text('customer_notes')->nullable()->after('amount_paid');
            $table->text('admin_notes')->nullable()->after('customer_notes');

            $table->boolean('contract_signed')->default(false)->after('admin_notes');
            $table->timestamp('contract_signed_at')->nullable()->after('contract_signed');
        });

        \DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('reserved','ongoing','completed','cancelled') NOT NULL DEFAULT 'reserved'");
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropForeign(['customer_user_id']);
            $table->dropColumn([
                'customer_user_id', 'rental_type', 'destination',
                'distance_km', 'distance_surcharge', 'driver_license_path',
                'payment_status', 'amount_paid', 'customer_notes',
                'admin_notes', 'contract_signed', 'contract_signed_at',
            ]);
        });
        \DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('ongoing','completed','cancelled') NOT NULL DEFAULT 'ongoing'");
    }
};