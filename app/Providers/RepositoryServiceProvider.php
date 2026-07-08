<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Contracts
use App\Repositories\Contracts\TenantRepositoryInterface;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;
use App\Repositories\Contracts\PlanRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

// Implementations
use App\Repositories\Central\TenantRepository;
use App\Repositories\Central\SubscriptionRepository;
use App\Repositories\Central\PlanRepository;
use App\Repositories\Tenant\OrderRepository;
use App\Repositories\Tenant\CustomerRepository;
use App\Repositories\Tenant\UserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TenantRepositoryInterface::class, TenantRepository::class);
        $this->app->bind(SubscriptionRepositoryInterface::class, SubscriptionRepository::class);
        $this->app->bind(PlanRepositoryInterface::class, PlanRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
