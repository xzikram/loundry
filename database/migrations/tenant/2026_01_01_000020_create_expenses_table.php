<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('expense_categories');
            $table->foreignId('outlet_id')->constrained('outlets');
            $table->foreignId('user_id')->constrained('users'); // Who recorded the expense
            $table->string('description');
            $table->decimal('amount', 12, 2);
            $table->date('expense_date');
            $table->string('receipt_photo')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
