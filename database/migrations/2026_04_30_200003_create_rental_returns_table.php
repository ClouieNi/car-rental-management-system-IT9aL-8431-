<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained()->cascadeOnDelete();
            $table->timestamp('returned_at');
            $table->foreignId('returned_by')->constrained('users')->cascadeOnDelete();
            
            // Vehicle condition
            $table->unsignedInteger('damage_panels')->default(0);
            $table->text('damage_description')->nullable();
            $table->json('damage_photos')->nullable(); // Array of file paths
            
            // Damage fee calculation
            $table->decimal('damage_fee', 10, 2)->default(0);
            $table->decimal('damage_rate_per_panel', 10, 2)->default(5000.00);
            
            // Other checks
            $table->enum('fuel_level', ['full', 'partial', 'empty'])->default('partial');
            $table->unsignedInteger('mileage_returned')->nullable();
            $table->unsignedInteger('mileage_start')->nullable();
            
            // Additional charges
            $table->decimal('fuel_charge', 10, 2)->default(0);
            $table->decimal('late_return_charge', 10, 2)->default(0);
            $table->decimal('cleaning_charge', 10, 2)->default(0);
            $table->decimal('other_charges', 10, 2)->default(0);
            $table->text('other_charges_notes')->nullable();
            
            // Totals
            $table->decimal('total_additional_charges', 10, 2)->default(0);
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_returns');
    }
};
