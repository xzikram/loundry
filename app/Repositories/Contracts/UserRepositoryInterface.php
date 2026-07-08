<?php

namespace App\Repositories\Contracts;

use App\Models\Tenant\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function allActive(): Collection;
    public function find(int $id): ?User;
    public function findByEmail(string $email): ?User;
    public function create(array $data): User;
    public function update(int $id, array $data): bool;
}
