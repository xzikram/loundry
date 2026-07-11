<?php

namespace App\Livewire\Tenant\Profile;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

#[Layout('layouts.tenant')]
class ProfilePage extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $saved = false;

    public function mount()
    {
        $user = auth('tenant')->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
    }

    public function save()
    {
        $user = auth('tenant')->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:20',
        ];

        if (!empty($this->password)) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }

        $this->validate($rules, [
            'email.unique' => 'Email ini sudah digunakan oleh staf lain.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal harus 6 karakter.',
        ]);

        $updateData = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
        ];

        if (!empty($this->password)) {
            $updateData['password'] = Hash::make($this->password);
        }

        $user->update($updateData);

        // Reset password fields
        $this->password = '';
        $this->password_confirmation = '';

        // Log activity
        \App\Models\Tenant\ActivityLog::create([
            'description' => 'User updated their profile settings',
            'causer_id' => $user->id,
            'properties' => [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ],
        ]);

        // Flash toast notification
        session()->flash('success', 'Profil Anda berhasil diperbarui.');
        $this->saved = true;
    }

    public function render()
    {
        return view('livewire.tenant.profile-page');
    }
}
