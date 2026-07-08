<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->onDelete('set null');
            $table->foreignId('outlet_id')->constrained('outlets')->onDelete('cascade');
            $table->date('date');
            $table->timestamp('clock_in')->nullable();
            $table->timestamp('clock_out')->nullable();
            $table->enum('status', ['present', 'late', 'absent', 'leave', 'sick'])->default('present');
            $table->text('notes')->nullable();
            $table->decimal('latitude_in', 10, 7)->nullable();
            $table->decimal('longitude_in', 10, 7)->nullable();
            $table->decimal('latitude_out', 10, 7)->nullable();
            $table->decimal('longitude_out', 10, 7)->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
