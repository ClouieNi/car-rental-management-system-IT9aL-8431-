<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('days');
            $table->enum('rental_type', ['with_driver', 'self_drive'])->default('self_drive');
            $table->string('destination')->nullable();
            $table->unsignedInteger('distance_km')->default(0);
            $table->decimal('base_cost', 10, 2);
            $table->decimal('distance_surcharge', 10, 2)->default(0);
            $table->decimal('total_estimate', 10, 2);
            $table->enum('status', ['pending', 'sent', 'accepted', 'rejected', 'expired', 'converted'])
                  ->default('pending');
            $table->text('guest_notes')->nullable();
            $table->text('admin_remarks')->nullable();
            $table->foreignId('rental_id')->nullable()->constrained('rentals')->nullOnDelete();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};