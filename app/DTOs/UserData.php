<?php

namespace App\DTOs;

class UserData
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $phone = null,
        public ?string $password = null,
        public ?string $avatar = null,
        public ?int $roleId = null,
        public ?int $outletId = null,
        public bool $isActive = true
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            phone: $data['phone'] ?? null,
            password: $data['password'] ?? null,
            avatar: $data['avatar'] ?? null,
            roleId: isset($data['role_id']) ? (int) $data['role_id'] : null,
            outletId: isset($data['outlet_id']) ? (int) $data['outlet_id'] : null,
            isActive: (bool) ($data['is_active'] ?? true)
        );
    }

    public function toArray(): array
    {
        $array = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
            'role_id' => $this->roleId,
            'outlet_id' => $this->outletId,
            'is_active' => $this->isActive,
        ];

        if ($this->password) {
            $array['password'] = $this->password; // hash handles model/observer side
        }

        return $array;
    }
}
