<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->string('vehicle_type', 20)->nullable()->after('year');
            $table->string('transmission', 20)->nullable()->after('vehicle_type');
            $table->string('fuel_type', 20)->nullable()->after('transmission');
            $table->integer('seating_capacity')->nullable()->after('fuel_type');
            $table->text('description')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn(['vehicle_type', 'transmission', 'fuel_type', 'seating_capacity', 'description']);
        });
    }
};
