<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->foreignId('plan_id')->constrained('plans')->onDelete('cascade');
            $table->enum('status', ['trial', 'active', 'expired', 'cancelled', 'past_due'])->default('trial');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->decimal('amount', 12, 2)->default(0.00);
            $table->string('currency', 3)->default('IDR');
            $table->string('payment_method')->nullable();
            $table->boolean('auto_renew')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
