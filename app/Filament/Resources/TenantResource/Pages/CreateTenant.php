<?php

namespace App\Filament\Resources\TenantResource\Pages;

use App\Filament\Resources\TenantResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Actions\Tenant\CreateTenantAction;
use App\DTOs\TenantData;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Pick the first central domain from config, default to laundrypromax.test in production or localhost in development
        $centralDomain = config('tenancy.central_domains.0') ?? 'laundrypromax.test';
        $domain = $data['id'] . '.' . $centralDomain;

        // Custom data for seeding the owner account
        $data['data'] = [
            'owner_name' => 'Owner ' . $data['name'],
            'owner_email' => $data['email'],
            'owner_phone' => $data['phone'] ?? '',
            'owner_password' => bcrypt('laundrypromax123'), // Default cashier/owner password
        ];

        $tenantData = TenantData::fromRequest($data);
        $action = app(CreateTenantAction::class);

        return $action->execute($tenantData, $domain);
    }
}
