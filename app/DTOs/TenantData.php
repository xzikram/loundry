<?php

namespace App\DTOs;

class TenantData
{
    public function __construct(
        public string $id,
        public string $name,
        public string $slug,
        public string $email,
        public ?string $phone = null,
        public ?string $logo = null,
        public string $status = 'trial',
        public ?string $trialEndsAt = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            id: $data['id'] ?? $data['slug'],
            name: $data['name'],
            slug: $data['slug'],
            email: $data['email'],
            phone: $data['phone'] ?? null,
            logo: $data['logo'] ?? null,
            status: $data['status'] ?? 'trial',
            trialEndsAt: $data['trial_ends_at'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'email' => $this->email,
            'phone' => $this->phone,
            'logo' => $this->logo,
            'status' => $this->status,
            'trial_ends_at' => $this->trialEndsAt,
        ];
    }
}
