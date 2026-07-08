<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->foreignId('outlet_id')->constrained('outlets')->onDelete('cascade');
            $table->string('price_type')->default('regular'); // regular, express, super_express
            $table->decimal('price', 12, 2);
            $table->decimal('min_weight', 8, 2)->default(0.00); // Minimum weight or quantity required
            $table->timestamps();

            $table->unique(['service_id', 'outlet_id', 'price_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_prices');
    }
};
