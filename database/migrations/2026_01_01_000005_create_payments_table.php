<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->onDelete('set null');
            $table->string('invoice_number')->unique();
            $table->decimal('amount', 12, 2);
            $table->decimal('tax', 12, 2)->default(0.00);
            $table->decimal('total', 12, 2);
            $table->string('currency', 3)->default('IDR');
            $table->enum('status', ['pending', 'paid', 'failed', 'expired', 'refunded'])->default('pending');
            $table->string('payment_method')->nullable(); // QRIS, VA, E-Wallet, Card
            $table->string('gateway_transaction_id')->nullable();
            $table->string('gateway_reference')->nullable();
            $table->json('gateway_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
