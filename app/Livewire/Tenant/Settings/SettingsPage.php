<?php

namespace App\Livewire\Tenant\Settings;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Tenant\Setting;
use App\Models\Tenant\Outlet;

#[Layout('layouts.tenant')]
class SettingsPage extends Component
{
    public string $laundryName = '';
    public string $laundryPhone = '';
    public string $laundryAddress = '';
    public string $laundryEmail = '';
    public float $taxRate = 11;
    public string $currency = 'IDR';
    public string $pakasirProjectSlug = '';
    public string $pakasirApiKey = '';
    public bool $saved = false;

    public function mount()
    {
        $this->laundryName = Setting::getValue('laundry_name', '');
        $this->laundryPhone = Setting::getValue('laundry_phone', '');
        $this->laundryAddress = Setting::getValue('laundry_address', '');
        $this->laundryEmail = Setting::getValue('laundry_email', '');
        $this->taxRate = (float) Setting::getValue('tax_rate', 11);
        $this->currency = Setting::getValue('currency', 'IDR');
        $this->pakasirProjectSlug = Setting::getValue('pakasir_project_slug', '');
        $this->pakasirApiKey = Setting::getValue('pakasir_api_key', '');
    }

    public function save()
    {
        $this->validate([
            'laundryName' => 'required|string|max:255',
            'laundryPhone' => 'nullable|string|max:20',
            'laundryAddress' => 'nullable|string|max:1000',
            'laundryEmail' => 'nullable|email|max:255',
            'taxRate' => 'required|numeric|min:0|max:100',
            'currency' => 'required|string|size:3',
            'pakasirProjectSlug' => 'nullable|string|max:255',
            'pakasirApiKey' => 'nullable|string|max:255',
        ]);

        Setting::setValue('laundry_name', $this->laundryName);
        Setting::setValue('laundry_phone', $this->laundryPhone);
        Setting::setValue('laundry_address', $this->laundryAddress);
        Setting::setValue('laundry_email', $this->laundryEmail);
        Setting::setValue('tax_rate', $this->taxRate);
        Setting::setValue('currency', $this->currency);
        Setting::setValue('pakasir_project_slug', $this->pakasirProjectSlug);
        Setting::setValue('pakasir_api_key', $this->pakasirApiKey);

        $this->saved = true;
    }

    public function render()
    {
        $outlet = Outlet::first();

        return view('livewire.tenant.settings-page', compact('outlet'));
    }
}
