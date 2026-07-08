<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function allActive(): Collection
    {
        return User::where('is_active', true)->with('role')->get();
    }

    public function find(int $id): ?User
    {
        return User::with(['role', 'outlet'])->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $user = User::find($id);
        if (!$user) {
            return false;
        }
        return $user->update($data);
    }
}
