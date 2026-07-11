<?php

namespace App\Livewire\Tenant\Services;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Tenant\Service;
use App\Models\Tenant\ServiceCategory;
use App\Models\Tenant\ServicePrice;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.tenant')]
class ServicesPage extends Component
{
    use WithPagination;

    public string $search = '';
    public int $filterCategoryId = 0;
    
    // Modal states
    public bool $showModal = false;
    public ?int $editingId = null;

    // Form inputs
    public ?int $categoryId = null;
    public string $serviceName = '';
    public string $description = '';
    public string $unit = 'kg';
    public int $estimatedDurationHours = 24;
    public bool $isActive = true;

    // Prices (regular, express, super_express)
    public $priceRegular = 0;
    public $minWeightRegular = 0;
    public $priceExpress = 0;
    public $minWeightExpress = 0;
    public $priceSuperExpress = 0;
    public $minWeightSuperExpress = 0;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterCategoryId() { $this->resetPage(); }

    public function openCreate()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $this->resetForm();
        $service = Service::with('prices')->findOrFail($id);
        $this->editingId = $service->id;
        $this->categoryId = $service->category_id;
        $this->serviceName = $service->name;
        $this->description = $service->description ?? '';
        $this->unit = $service->unit;
        $this->estimatedDurationHours = $service->estimated_duration_hours;
        $this->isActive = $service->is_active;

        $outletId = Auth::guard('tenant')->user()->outlet_id;

        // Load prices
        $priceReg = $service->prices->where('outlet_id', $outletId)->where('price_type', 'regular')->first();
        $priceExp = $service->prices->where('outlet_id', $outletId)->where('price_type', 'express')->first();
        $priceSuper = $service->prices->where('outlet_id', $outletId)->where('price_type', 'super_express')->first();

        if ($priceReg) {
            $this->priceRegular = $priceReg->price;
            $this->minWeightRegular = $priceReg->min_weight;
        }
        if ($priceExp) {
            $this->priceExpress = $priceExp->price;
            $this->minWeightExpress = $priceExp->min_weight;
        }
        if ($priceSuper) {
            $this->priceSuperExpress = $priceSuper->price;
            $this->minWeightSuperExpress = $priceSuper->min_weight;
        }

        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->reset([
            'editingId', 'categoryId', 'serviceName', 'description', 'unit', 
            'estimatedDurationHours', 'isActive', 'priceRegular', 'minWeightRegular',
            'priceExpress', 'minWeightExpress', 'priceSuperExpress', 'minWeightSuperExpress'
        ]);
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save()
    {
        $this->validate([
            'categoryId' => 'required|exists:service_categories,id',
            'serviceName' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'unit' => 'required|string|in:kg,pcs,meter,pasang',
            'estimatedDurationHours' => 'required|integer|min:1',
            'priceRegular' => 'required|numeric|min:0',
            'priceExpress' => 'required|numeric|min:0',
            'priceSuperExpress' => 'required|numeric|min:0',
        ]);

        $data = [
            'category_id' => $this->categoryId,
            'name' => $this->serviceName,
            'slug' => Str::slug($this->serviceName) . '-' . rand(100, 999), // guarantee uniqueness
            'description' => $this->description,
            'unit' => $this->unit,
            'estimated_duration_hours' => $this->estimatedDurationHours,
            'is_active' => $this->isActive,
        ];

        if ($this->editingId) {
            $service = Service::findOrFail($this->editingId);
            // keep old slug if name not changed or just update it
            if ($service->name !== $this->serviceName) {
                $data['slug'] = Str::slug($this->serviceName) . '-' . rand(100, 999);
            } else {
                unset($data['slug']);
            }
            $service->update($data);
        } else {
            $service = Service::create($data);
        }

        $outletId = Auth::guard('tenant')->user()->outlet_id;

        // Upsert prices for the current outlet
        $pricingList = [
            'regular' => [$this->priceRegular, $this->minWeightRegular],
            'express' => [$this->priceExpress, $this->minWeightExpress],
            'super_express' => [$this->priceSuperExpress, $this->minWeightSuperExpress]
        ];

        foreach ($pricingList as $type => $info) {
            ServicePrice::updateOrCreate(
                [
                    'service_id' => $service->id,
                    'outlet_id' => $outletId,
                    'price_type' => $type,
                ],
                [
                    'price' => $info[0],
                    'min_weight' => $info[1] ?? 0.00
                ]
            );
        }

        // Log tenant activity log
        \App\Models\Tenant\ActivityLog::create([
            'description' => ($this->editingId ? 'Memperbarui' : 'Membuat') . ' layanan ' . $service->name . ' beserta tarif harganya.',
            'causer_id' => Auth::guard('tenant')->id(),
        ]);

        $this->showModal = false;
        $this->resetForm();
        session()->flash('message', 'Layanan berhasil disimpan!');
    }

    public function delete($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        // Log tenant activity log
        \App\Models\Tenant\ActivityLog::create([
            'description' => 'Menghapus layanan: ' . $service->name,
            'causer_id' => Auth::guard('tenant')->id(),
        ]);

        session()->flash('message', 'Layanan berhasil dihapus!');
    }

    public function render()
    {
        $outletId = Auth::guard('tenant')->user()->outlet_id;

        $query = Service::with(['category', 'prices' => function($q) use ($outletId) {
            $q->where('outlet_id', $outletId);
        }]);

        if ($this->search) {
            $query->where('name', 'like', '%' . addcslashes($this->search, '%_') . '%');
        }

        if ($this->filterCategoryId) {
            $query->where('category_id', $this->filterCategoryId);
        }

        $services = $query->latest()->paginate(15);
        $categories = ServiceCategory::where('is_active', true)->orderBy('sort_order')->get();

        return view('livewire.tenant.services-page', compact('services', 'categories'));
    }
}
