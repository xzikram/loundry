<?php

namespace App\Livewire\Tenant\Customers;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Tenant\Customer;

#[Layout('layouts.tenant')]
class CustomerListPage extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public ?int $editingCustomerId = null;

    public string $name = '';
    public string $phone = '';
    public string $email = '';
    public string $address = '';

    public function updatingSearch() { $this->resetPage(); }

    public function openCreate()
    {
        $this->reset(['name', 'phone', 'email', 'address', 'editingCustomerId']);
        $this->showCreateModal = true;
    }

    public function openEdit($id)
    {
        $customer = Customer::findOrFail($id);
        $this->editingCustomerId = $customer->id;
        $this->name = $customer->name;
        $this->phone = $customer->phone ?? '';
        $this->email = $customer->email ?? '';
        $this->address = $customer->address ?? '';
        $this->showEditModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        Customer::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
        ]);

        $this->showCreateModal = false;
        $this->reset(['name', 'phone', 'email', 'address']);
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $customer = Customer::findOrFail($this->editingCustomerId);
        $customer->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
        ]);

        $this->showEditModal = false;
        $this->reset(['name', 'phone', 'email', 'address', 'editingCustomerId']);
    }

    public function delete($id)
    {
        Customer::findOrFail($id)->delete();
    }

    public function render()
    {
        $customers = Customer::where('name', 'like', '%' . addcslashes($this->search, '%_') . '%')
            ->orWhere('phone', 'like', '%' . addcslashes($this->search, '%_') . '%')
            ->latest()
            ->paginate(15);

        return view('livewire.tenant.customer-list-page', compact('customers'));
    }
}
