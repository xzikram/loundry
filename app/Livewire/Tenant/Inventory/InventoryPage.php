<?php

namespace App\Livewire\Tenant\Inventory;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Tenant\Inventory;

#[Layout('layouts.tenant')]
class InventoryPage extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public ?int $editingId = null;

    public string $itemName = '';
    public string $unit = 'liter';
    public float $currentStock = 0;
    public float $minStock = 5;
    public float $pricePerUnit = 0;
    public string $supplier = '';

    public function updatingSearch() { $this->resetPage(); }

    public function openCreate()
    {
        $this->reset(['itemName', 'unit', 'currentStock', 'minStock', 'pricePerUnit', 'supplier', 'editingId']);
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $item = Inventory::findOrFail($id);
        $this->editingId = $item->id;
        $this->itemName = $item->name;
        $this->unit = $item->unit;
        $this->currentStock = $item->current_stock;
        $this->minStock = $item->min_stock;
        $this->pricePerUnit = $item->price_per_unit ?? 0;
        $this->supplier = $item->supplier ?? '';
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'itemName' => 'required|string|max:255',
            'unit' => 'required|string',
            'currentStock' => 'required|numeric|min:0',
            'minStock' => 'required|numeric|min:0',
            'pricePerUnit' => 'required|numeric|min:0',
        ]);

        $data = [
            'name' => $this->itemName,
            'unit' => $this->unit,
            'current_stock' => $this->currentStock,
            'min_stock' => $this->minStock,
            'price_per_unit' => $this->pricePerUnit,
            'supplier' => $this->supplier,
        ];

        if ($this->editingId) {
            Inventory::findOrFail($this->editingId)->update($data);
        } else {
            Inventory::create($data);
        }

        $this->showModal = false;
        $this->reset(['itemName', 'unit', 'currentStock', 'minStock', 'pricePerUnit', 'supplier', 'editingId']);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['itemName', 'unit', 'currentStock', 'minStock', 'pricePerUnit', 'supplier', 'editingId']);
        $this->resetValidation();
    }

    public function delete($id) { Inventory::findOrFail($id)->delete(); }

    public function render()
    {
        $items = Inventory::where('name', 'like', '%' . addcslashes($this->search, '%_') . '%')
            ->latest()->paginate(15);

        return view('livewire.tenant.inventory-page', compact('items'));
    }
}
