<?php

namespace App\Livewire\Tenant\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Tenant\Setting;
use App\Models\Tenant\Service;
use App\Models\Tenant\ServiceCategory;
use App\Models\Tenant\Outlet;
use Illuminate\Support\Collection;

#[Layout('layouts.auth')] // Reuse the clean, premium auth layout
class TenantLandingPage extends Component
{
    public string $laundryName = '';
    public string $laundryPhone = '';
    public string $laundryAddress = '';
    public string $laundryEmail = '';
    public Collection $categories;
    public Collection $services;
    public ?Outlet $outlet = null;

    // Tracking state
    public string $invoiceNumber = '';

    // Dynamic Page states
    public ?\App\Models\Tenant\LandingPage $dynamicPage = null;
    public $sections = [];
    public $theme = null;

    public function mount()
    {
        // Check for active published homepage
        $this->dynamicPage = \App\Models\Tenant\LandingPage::where('is_homepage', true)
            ->where('status', 'published')
            ->first();

        if ($this->dynamicPage) {
            $this->sections = \App\Models\Tenant\LandingSection::where('landing_page_id', $this->dynamicPage->id)
                ->where('is_visible', true)
                ->orderBy('sort_order')
                ->get();
            $this->theme = \App\Models\Tenant\LandingThemeSetting::getSettings();
        } else {
            $this->laundryName = Setting::getValue('laundry_name', 'Spinly Laundry');
            $this->laundryPhone = Setting::getValue('laundry_phone', '');
            $this->laundryAddress = Setting::getValue('laundry_address', '');
            $this->laundryEmail = Setting::getValue('laundry_email', '');
            
            $this->outlet = Outlet::first();
            if ($this->outlet) {
                if (empty($this->laundryName)) $this->laundryName = $this->outlet->name;
                if (empty($this->laundryPhone)) $this->laundryPhone = $this->outlet->phone;
                if (empty($this->laundryAddress)) $this->laundryAddress = $this->outlet->address;
            }

            $this->categories = ServiceCategory::with('services')->where('is_active', true)->get();
            $this->services = Service::with('prices')->where('is_active', true)->take(8)->get();
        }
    }

    public function track()
    {
        $this->validate([
            'invoiceNumber' => 'required|string',
        ]);

        return redirect()->route('tenant.track', $this->invoiceNumber);
    }

    public function render()
    {
        return view('livewire.tenant.auth.tenant-landing-page');
    }
}
