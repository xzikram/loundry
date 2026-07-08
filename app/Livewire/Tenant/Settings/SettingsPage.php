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
    public bool $saved = false;

    public function mount()
    {
        $this->laundryName = Setting::getValue('laundry_name', '');
        $this->laundryPhone = Setting::getValue('laundry_phone', '');
        $this->laundryAddress = Setting::getValue('laundry_address', '');
        $this->laundryEmail = Setting::getValue('laundry_email', '');
        $this->taxRate = (float) Setting::getValue('tax_rate', 11);
        $this->currency = Setting::getValue('currency', 'IDR');
    }

    public function save()
    {
        $this->validate([
            'laundryName' => 'required|string|max:255',
            'taxRate' => 'required|numeric|min:0|max:100',
        ]);

        Setting::setValue('laundry_name', $this->laundryName);
        Setting::setValue('laundry_phone', $this->laundryPhone);
        Setting::setValue('laundry_address', $this->laundryAddress);
        Setting::setValue('laundry_email', $this->laundryEmail);
        Setting::setValue('tax_rate', $this->taxRate);
        Setting::setValue('currency', $this->currency);

        $this->saved = true;
    }

    public function render()
    {
        $outlet = Outlet::first();

        return view('livewire.tenant.settings-page', compact('outlet'));
    }
}
