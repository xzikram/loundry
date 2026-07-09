<?php

namespace App\Livewire\Central\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Central\CentralUser;
use App\Models\Central\Tenant;
use App\Enums\UserRole;
use App\Enums\TenantStatus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

#[Layout('layouts.auth')] // Use the clean, premium auth layout
class RegisterPage extends Component
{
    public string $fullName = '';
    public string $email = '';
    public string $phone = '';
    public string $laundryName = '';
    public string $subdomain = '';
    public string $password = '';
    public string $password_confirmation = '';

    public string $errorMessage = '';

    protected array $rules = [
        'fullName' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:central_users,email',
        'phone' => 'required|string|max:20',
        'laundryName' => 'required|string|max:255',
        'subdomain' => 'required|string|alpha_dash|min:3|max:50|unique:tenants,id',
        'password' => 'required|string|min:6|confirmed',
    ];

    protected array $messages = [
        'subdomain.unique' => 'Subdomain ini sudah digunakan oleh laundry lain.',
        'subdomain.alpha_dash' => 'Subdomain hanya boleh berisi huruf, angka, dan strip (-).',
        'email.unique' => 'Email ini sudah terdaftar.',
    ];

    public function updatedSubdomain()
    {
        $this->subdomain = Str::slug($this->subdomain);
    }

    public function register()
    {
        $this->validate();

        try {
            // 1. Create tenant instance and assign properties before saving (required for Stancl/Tenancy JSON serialization)
            $tenant = new Tenant();
            $tenant->id = $this->subdomain;
            $tenant->name = $this->laundryName;
            $tenant->slug = $this->subdomain;
            $tenant->email = $this->email;
            $tenant->phone = $this->phone;
            $tenant->status = TenantStatus::TRIAL;
            $tenant->trial_ends_at = now()->addDays(3);
            
            // Custom properties to be saved in JSON 'data' field (read by TenantDatabaseSeeder)
            $tenant->owner_name = $this->fullName;
            $tenant->owner_email = $this->email;
            $tenant->owner_password = Hash::make($this->password);
            $tenant->owner_phone = $this->phone;
            
            $tenant->save();

            // 2. Create domains mapping
            $host = request()->getHost();
            if ($host === '127.0.0.1') {
                $host = 'localhost';
            }
            
            // Add primary subdomain mapping (e.g. demo.localhost)
            $tenant->domains()->create([
                'domain' => $this->subdomain . '.' . $host,
            ]);
            
            // Add subdomain lookup mapping (needed by resolver for parsing)
            $tenant->domains()->create([
                'domain' => $this->subdomain,
            ]);

            // 3. Redirect to tenant subdomain login page
            $port = request()->getPort() ? ':' . request()->getPort() : '';
            $scheme = request()->getScheme();
            $loginUrl = $scheme . '://' . $this->subdomain . '.' . $host . $port . '/login?email=' . urlencode($this->email);
            
            return redirect()->away($loginUrl);

        } catch (\Exception $e) {
            $this->errorMessage = 'Gagal melakukan pendaftaran: ' . $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.central.auth.register-page');
    }
}
