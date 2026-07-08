<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained('outlets')->onDelete('cascade');
            $table->string('name');
            $table->string('sku')->unique();
            $table->string('unit')->default('pcs'); // pcs, liter, ml
            $table->decimal('quantity', 12, 2)->default(0.00);
            $table->decimal('min_stock', 12, 2)->default(0.00);
            $table->decimal('price_per_unit', 12, 2)->default(0.00);
            $table->string('supplier')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
