<?php

namespace App\Http\Controllers\Central\Auth;

use App\Http\Controllers\Controller;
use App\Models\Central\CentralUser;
use App\Models\Central\Tenant;
use App\Enums\UserRole;
use App\Enums\TenantStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal masuk dengan Google: ' . $e->getMessage());
        }

        // Check if central user already exists by google_id or email
        $user = CentralUser::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (!$user) {
            // Check if tenant exists with this email (user registered normally without Google first)
            $tenant = Tenant::where('email', $googleUser->getEmail())->first();
            if ($tenant) {
                $user = CentralUser::create([
                    'name' => $googleUser->getName() ?? $tenant->name,
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'role' => UserRole::ADMIN,
                    'is_active' => true,
                ]);
            }
        }

        if ($user) {
            // Update google_id if it wasn't set
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }

            // Log in central user
            Auth::guard('web')->login($user, true);

            // Find associated tenant
            $tenant = Tenant::where('email', $user->email)->first();
            if ($tenant) {
                // Generate secure temporary SSO token
                $ssoToken = Str::random(60);
                $user->update([
                    'sso_token' => $ssoToken,
                    'sso_token_expires_at' => now()->addMinutes(5),
                ]);

                // Determine redirect subdomain
                $host = request()->getHost();
                if ($host === '127.0.0.1') {
                    $host = 'localhost';
                }
                
                $domainModel = $tenant->domains()->first();
                $tenantDomain = $domainModel ? $domainModel->domain : '';
                
                if (!$tenantDomain) {
                    $separator = ($host === 'localhost' || $host === '127.0.0.1') ? '.' : '-';
                    $tenantDomain = $tenant->id . $separator . $host;
                }
                
                $port = request()->getPort() ? ':' . request()->getPort() : '';
                if ($port && !str_contains($tenantDomain, ':')) {
                    $tenantDomain .= $port;
                }
                
                $scheme = request()->getScheme();
                return redirect()->away($scheme . '://' . $tenantDomain . '/login?sso_token=' . $ssoToken);
            }

            // If no tenant (e.g. super admin), redirect to central admin panel
            return redirect()->to(url('/admin'));
        } else {
            // User does not exist. This is a Registration flow!
            // Store google user info in session
            session([
                'google_reg_name' => $googleUser->getName(),
                'google_reg_email' => $googleUser->getEmail(),
                'google_reg_id' => $googleUser->getId(),
            ]);

            return redirect()->route('register.complete');
        }
    }

    /**
     * Show the form to complete the registration.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showCompleteRegistrationForm()
    {
        if (!session()->has('google_reg_email')) {
            return redirect()->route('register');
        }

        return view('auth.google-complete', [
            'name' => session('google_reg_name'),
            'email' => session('google_reg_email'),
        ]);
    }

    /**
     * Process the registration completion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function completeRegistration(Request $request)
    {
        if (!session()->has('google_reg_email')) {
            return redirect()->route('register');
        }

        $request->validate([
            'laundryName' => 'required|string|max:255',
            'subdomain' => 'required|string|alpha_dash|min:3|max:50|unique:tenants,id',
            'phone' => 'required|string|max:20',
        ], [
            'subdomain.unique' => 'Subdomain ini sudah digunakan oleh laundry lain.',
            'subdomain.alpha_dash' => 'Subdomain hanya boleh berisi huruf, angka, dan strip (-).',
            'laundryName.required' => 'Nama laundry wajib diisi.',
            'subdomain.required' => 'Subdomain wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
        ]);

        $name = session('google_reg_name');
        $email = session('google_reg_email');
        $googleId = session('google_reg_id');

        try {
            // Create CentralUser
            $centralUser = CentralUser::create([
                'name' => $name,
                'email' => $email,
                'google_id' => $googleId,
                'role' => UserRole::ADMIN,
                'is_active' => true,
            ]);

            // Create Tenant
            $tenant = new Tenant();
            $tenant->id = Str::slug($request->subdomain);
            $tenant->name = $request->laundryName;
            $tenant->slug = Str::slug($request->subdomain);
            $tenant->email = $email;
            $tenant->phone = $request->phone;
            $tenant->status = TenantStatus::TRIAL;
            $tenant->trial_ends_at = now()->addDays(3);
            
            // Custom properties for TenantDatabaseSeeder
            $tenant->owner_name = $name;
            $tenant->owner_email = $email;
            $tenant->owner_password = Hash::make(Str::random(16)); // Random password for google users
            $tenant->owner_phone = $request->phone;
            
            $tenant->save();

            // Create domains mapping
            $host = request()->getHost();
            if ($host === '127.0.0.1') {
                $host = 'localhost';
            }
            
            $separator = ($host === 'localhost' || $host === '127.0.0.1') ? '.' : '-';
            $tenantDomain = $tenant->id . $separator . $host;

            $tenant->domains()->create([
                'domain' => $tenantDomain,
            ]);
            
            $tenant->domains()->create([
                'domain' => $tenant->id,
            ]);

            // Clear session keys
            session()->forget(['google_reg_name', 'google_reg_email', 'google_reg_id']);

            // Login central user
            Auth::guard('web')->login($centralUser, true);

            // Generate secure temporary SSO token
            $ssoToken = Str::random(60);
            $centralUser->update([
                'sso_token' => $ssoToken,
                'sso_token_expires_at' => now()->addMinutes(5),
            ]);

            $port = request()->getPort() ? ':' . request()->getPort() : '';
            $scheme = request()->getScheme();
            $loginUrl = $scheme . '://' . $tenantDomain . $port . '/login?sso_token=' . $ssoToken;
            
            return redirect()->away($loginUrl);

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Gagal menyelesaikan pendaftaran: ' . $e->getMessage()]);
        }
    }
}
