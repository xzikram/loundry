<?php

namespace App\Repositories\Contracts;

use App\Models\Central\Plan;
use Illuminate\Support\Collection;

interface PlanRepositoryInterface
{
    public function allActive(): Collection;
    public function find(int $id): ?Plan;
    public function findBySlug(string $slug): ?Plan;
    public function create(array $data): Plan;
    public function update(int $id, array $data): bool;
}
