<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained('outlets')->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['thermal', 'inkjet', 'laser'])->default('thermal');
            $table->enum('connection', ['usb', 'bluetooth', 'network', 'cloud'])->default('usb');
            $table->string('ip_address')->nullable();
            $table->integer('port')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('config')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('printers');
    }
};
