<?php

namespace App\Repositories\Central;

use App\Models\Central\Tenant;
use App\Repositories\Contracts\TenantRepositoryInterface;
use Illuminate\Support\Collection;

class TenantRepository implements TenantRepositoryInterface
{
    public function all(): Collection
    {
        return Tenant::with('domains')->get();
    }

    public function find(string $id): ?Tenant
    {
        return Tenant::with('domains')->find($id);
    }

    public function findBySlug(string $slug): ?Tenant
    {
        return Tenant::where('slug', $slug)->first();
    }

    public function create(array $data): Tenant
    {
        return Tenant::create($data);
    }

    public function update(string $id, array $data): bool
    {
        $tenant = Tenant::find($id);
        if (!$tenant) {
            return false;
        }
        return $tenant->update($data);
    }

    public function delete(string $id): bool
    {
        $tenant = Tenant::find($id);
        if (!$tenant) {
            return false;
        }
        return (bool) $tenant->delete();
    }

    public function updateStatus(string $id, string $status): bool
    {
        $tenant = Tenant::find($id);
        if (!$tenant) {
            return false;
        }
        $tenant->status = $status;
        return $tenant->save();
    }
}
