<?php

namespace App\DTOs;

class PaymentData
{
    public function __construct(
        public string $tenantId,
        public ?int $subscriptionId,
        public string $invoiceNumber,
        public float $amount,
        public float $tax = 0.00,
        public float $total,
        public string $currency = 'IDR',
        public string $status = 'pending',
        public ?string $paymentMethod = null,
        public ?string $gatewayTransactionId = null,
        public ?string $gatewayReference = null,
        public ?array $gatewayResponse = null,
        public ?string $notes = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            tenantId: $data['tenant_id'],
            subscriptionId: isset($data['subscription_id']) ? (int) $data['subscription_id'] : null,
            invoiceNumber: $data['invoice_number'],
            amount: (float) $data['amount'],
            tax: (float) ($data['tax'] ?? 0.00),
            total: (float) $data['total'],
            currency: $data['currency'] ?? 'IDR',
            status: $data['status'] ?? 'pending',
            paymentMethod: $data['payment_method'] ?? null,
            gatewayTransactionId: $data['gateway_transaction_id'] ?? null,
            gatewayReference: $data['gateway_reference'] ?? null,
            gatewayResponse: $data['gateway_response'] ?? null,
            notes: $data['notes'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'tenant_id' => $this->tenantId,
            'subscription_id' => $this->subscriptionId,
            'invoice_number' => $this->invoiceNumber,
            'amount' => $this->amount,
            'tax' => $this->tax,
            'total' => $this->total,
            'currency' => $this->currency,
            'status' => $this->status,
            'payment_method' => $this->paymentMethod,
            'gateway_transaction_id' => $this->gatewayTransactionId,
            'gateway_reference' => $this->gatewayReference,
            'gateway_response' => $this->gatewayResponse ? json_encode($this->gatewayResponse) : null,
            'notes' => $this->notes,
        ];
    }
}
