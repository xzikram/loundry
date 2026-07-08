<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('outlet_id')->constrained('outlets');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', [
                'pending', 'processing', 'washing', 'drying', 'ironing', 'packing', 
                'ready', 'picked_up', 'delivered', 'completed', 'cancelled'
            ])->default('pending');
            $table->enum('priority', ['regular', 'express', 'super_express'])->default('regular');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount', 12, 2)->default(0.00);
            $table->decimal('tax', 12, 2)->default(0.00);
            $table->decimal('total', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0.00);
            $table->decimal('change_amount', 12, 2)->default(0.00);
            $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'refunded'])->default('unpaid');
            $table->text('notes')->nullable();
            $table->text('special_instructions')->nullable();
            $table->timestamp('estimated_completion')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->string('qr_code')->nullable();
            $table->string('barcode')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
