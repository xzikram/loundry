<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->unique(); // Typically laundry customers are identified by phone
            $table->text('address')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('birth_date')->nullable();
            $table->string('membership_code')->unique()->nullable();
            $table->integer('total_orders')->default(0);
            $table->decimal('total_spent', 12, 2)->default(0.00);
            $table->integer('loyalty_points')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
