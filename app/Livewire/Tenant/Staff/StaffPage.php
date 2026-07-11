<?php

namespace App\Livewire\Tenant\Staff;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Tenant\User;
use App\Models\Tenant\Role;
use Illuminate\Support\Facades\Hash;

#[Layout('layouts.tenant')]
class StaffPage extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public ?int $editingId = null;

    public string $staffName = '';
    public string $staffEmail = '';
    public string $staffPhone = '';
    public string $staffPassword = '';
    public ?int $roleId = null;

    public function updatingSearch() { $this->resetPage(); }

    public function openCreate()
    {
        $this->reset(['staffName', 'staffEmail', 'staffPhone', 'staffPassword', 'roleId', 'editingId']);
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $user = User::findOrFail($id);
        $this->editingId = $user->id;
        $this->staffName = $user->name;
        $this->staffEmail = $user->email;
        $this->staffPhone = $user->phone ?? '';
        $this->roleId = $user->role_id;
        $this->staffPassword = '';
        $this->showModal = true;
    }

    public function save()
    {
        $rules = [
            'staffName' => 'required|string|max:255',
            'staffEmail' => 'required|email|max:255',
            'roleId' => 'required|exists:roles,id',
        ];

        if (!$this->editingId) {
            $rules['staffPassword'] = 'required|string|min:6';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->staffName,
            'email' => $this->staffEmail,
            'phone' => $this->staffPhone,
            'role_id' => $this->roleId,
            'is_active' => true,
            'outlet_id' => auth('tenant')->user()->outlet_id,
        ];

        if ($this->staffPassword) {
            $data['password'] = Hash::make($this->staffPassword);
        }

        if ($this->editingId) {
            User::findOrFail($this->editingId)->update($data);
        } else {
            User::create($data);
        }

        $this->showModal = false;
        $this->reset(['staffName', 'staffEmail', 'staffPhone', 'staffPassword', 'roleId', 'editingId']);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['staffName', 'staffEmail', 'staffPhone', 'staffPassword', 'roleId', 'editingId']);
        $this->resetValidation();
    }

    public function toggleActive($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
    }

    public function render()
    {
        $staff = User::with('role')
            ->where('name', 'like', '%' . addcslashes($this->search, '%_') . '%')
            ->latest()->paginate(15);
        $roles = Role::all();

        return view('livewire.tenant.staff-page', compact('staff', 'roles'));
    }
}
