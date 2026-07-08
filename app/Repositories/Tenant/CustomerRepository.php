<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Support\Collection;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function all(): Collection
    {
        return Customer::all();
    }

    public function find(int $id): ?Customer
    {
        return Customer::find($id);
    }

    public function findByPhone(string $phone): ?Customer
    {
        return Customer::where('phone', $phone)->first();
    }

    public function create(array $data): Customer
    {
        return Customer::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return false;
        }
        return $customer->update($data);
    }

    public function incrementSpent(int $id, float $amount): bool
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return false;
        }

        $customer->total_orders += 1;
        $customer->total_spent += $amount;
        $customer->loyalty_points += (int) ($amount / 10000); // 1 point per 10k IDR spent
        return $customer->save();
    }
}
