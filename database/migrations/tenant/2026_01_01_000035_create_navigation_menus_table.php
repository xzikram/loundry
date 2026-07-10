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
        Schema::create('navigation_menus', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('url');
            $table->string('target')->default('_self'); // _self, _blank
            $table->integer('sort_order')->default(0);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->string('menu_type')->default('header'); // header, footer
            $table->timestamps();
            
            $table->foreign('parent_id')->references('id')->on('navigation_menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navigation_menus');
    }
};
