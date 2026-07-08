<?php

namespace App\DTOs;

class SubscriptionData
{
    public function __construct(
        public string $tenantId,
        public int $planId,
        public string $status = 'trial',
        public ?string $startsAt = null,
        public ?string $endsAt = null,
        public ?string $trialEndsAt = null,
        public float $amount = 0.00,
        public string $currency = 'IDR',
        public ?string $paymentMethod = null,
        public bool $autoRenew = true,
        public ?array $metadata = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            tenantId: $data['tenant_id'],
            planId: (int) $data['plan_id'],
            status: $data['status'] ?? 'trial',
            startsAt: $data['starts_at'] ?? null,
            endsAt: $data['ends_at'] ?? null,
            trialEndsAt: $data['trial_ends_at'] ?? null,
            amount: (float) ($data['amount'] ?? 0.00),
            currency: $data['currency'] ?? 'IDR',
            paymentMethod: $data['payment_method'] ?? null,
            autoRenew: (bool) ($data['auto_renew'] ?? true),
            metadata: $data['metadata'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'tenant_id' => $this->tenantId,
            'plan_id' => $this->planId,
            'status' => $this->status,
            'starts_at' => $this->startsAt,
            'ends_at' => $this->endsAt,
            'trial_ends_at' => $this->trialEndsAt,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'payment_method' => $this->paymentMethod,
            'auto_renew' => $this->autoRenew,
            'metadata' => $this->metadata ? json_encode($this->metadata) : null,
        ];
    }
}
