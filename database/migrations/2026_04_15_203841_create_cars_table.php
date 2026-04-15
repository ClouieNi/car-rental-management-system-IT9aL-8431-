<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number', 20)->unique();
            $table->string('brand', 50);
            $table->string('model', 50);
            $table->integer('year');
            $table->decimal('daily_rate', 10, 2);
            $table->enum('status', ['available', 'rented', 'maintenance'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};