<?php

namespace App\Repositories\Contracts;

use App\Models\Tenant\Customer;
use Illuminate\Support\Collection;

interface CustomerRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Customer;
    public function findByPhone(string $phone): ?Customer;
    public function create(array $data): Customer;
    public function update(int $id, array $data): bool;
    public function incrementSpent(int $id, float $amount): bool;
}
