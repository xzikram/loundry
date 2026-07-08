<?php

namespace App\Repositories\Contracts;

use App\Models\Central\Tenant;
use Illuminate\Support\Collection;

interface TenantRepositoryInterface
{
    public function all(): Collection;
    public function find(string $id): ?Tenant;
    public function findBySlug(string $slug): ?Tenant;
    public function create(array $data): Tenant;
    public function update(string $id, array $data): bool;
    public function delete(string $id): bool;
    public function updateStatus(string $id, string $status): bool;
}
