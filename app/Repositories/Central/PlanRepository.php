<?php

namespace App\Repositories\Central;

use App\Models\Central\Plan;
use App\Repositories\Contracts\PlanRepositoryInterface;
use Illuminate\Support\Collection;

class PlanRepository implements PlanRepositoryInterface
{
    public function allActive(): Collection
    {
        return Plan::where('is_active', true)->orderBy('sort_order')->get();
    }

    public function find(int $id): ?Plan
    {
        return Plan::find($id);
    }

    public function findBySlug(string $slug): ?Plan
    {
        return Plan::where('slug', $slug)->first();
    }

    public function create(array $data): Plan
    {
        return Plan::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $plan = Plan::find($id);
        if (!$plan) {
            return false;
        }
        return $plan->update($data);
    }
}
