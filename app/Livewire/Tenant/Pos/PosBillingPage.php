<?php

namespace App\Livewire\Tenant\Pos;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Tenant\Customer;
use App\Models\Tenant\ServiceCategory;
use App\Models\Tenant\Service;
use App\Models\Tenant\ServicePrice;
use App\Models\Tenant\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Enums\OrderStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.tenant')]
class PosBillingPage extends Component
{
    // Search
    public string $customerSearch = '';
    public string $newCustomerName = '';
    public string $newCustomerPhone = '';
    
    // POS States
    public Collection $customers;
    public Collection $categories;
    public Collection $services;
    
    public ?int $selectedCustomerId = null;
    public ?Customer $selectedCustomer = null;
    public int $selectedCategoryId = 0;
    
    // Cart
    public array $cart = [];
    public string $priority = 'regular'; // regular, express, super_express
    public string $notes = '';
    public string $specialInstructions = '';
    
    // Modals
    public bool $showCustomerModal = false;
    public bool $showPaymentModal = false;
    public bool $showAiScanModal = false;
    
    // Scale and AI States
    public bool $isScaleConnected = false;
    public $scaleWeight = 0.00;
    public bool $isAiScanning = false;
    public ?int $selectedCartItemId = null;
    
    // Payment info
    public $paidAmount = 0.00;
    public string $paymentMethod = 'cash'; // cash, transfer, qris
    public string $referenceNumber = '';

    public function mount()
    {
        $this->loadCustomers();
        $this->categories = ServiceCategory::where('is_active', true)->orderBy('sort_order')->get();
        $this->selectedCategoryId = $this->categories->first()?->id ?? 0;
        $this->loadServices();
    }

    public function loadCustomers()
    {
        $searchEscaped = addcslashes($this->customerSearch, '%_');
        $this->customers = Customer::where('name', 'like', '%' . $searchEscaped . '%')
            ->orWhere('phone', 'like', '%' . $searchEscaped . '%')
            ->take(5)
            ->get();
    }

    public function updatedCustomerSearch()
    {
        $this->loadCustomers();
    }

    public function selectCustomer($id)
    {
        $this->selectedCustomerId = $id;
        $this->selectedCustomer = Customer::find($id);
        $this->customerSearch = '';
        $this->loadCustomers();
    }

    public function deselectCustomer()
    {
        $this->selectedCustomerId = null;
        $this->selectedCustomer = null;
    }

    public function createCustomer()
    {
        $this->validate([
            'newCustomerName' => 'required|string|max:255',
            'newCustomerPhone' => 'required|string|max:20',
        ]);

        $customer = Customer::create([
            'name' => $this->newCustomerName,
            'phone' => $this->newCustomerPhone,
        ]);

        $this->selectCustomer($customer->id);
        $this->newCustomerName = '';
        $this->newCustomerPhone = '';
        $this->showCustomerModal = false;
    }

    public function openCustomerModal()
    {
        $this->reset(['newCustomerName', 'newCustomerPhone']);
        $this->resetValidation();
        $this->showCustomerModal = true;
    }

    public function closeCustomerModal()
    {
        $this->showCustomerModal = false;
        $this->reset(['newCustomerName', 'newCustomerPhone']);
        $this->resetValidation();
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->resetValidation();
    }

    public function closeAiScanModal()
    {
        $this->showAiScanModal = false;
        $this->isAiScanning = false;
        $this->resetValidation();
    }

    public function filterCategory($id)
    {
        $this->selectedCategoryId = $id;
        $this->loadServices();
    }

    public function loadServices()
    {
        $this->services = Service::where('category_id', $this->selectedCategoryId)
            ->where('is_active', true)
            ->with(['prices' => function ($query) {
                $query->where('outlet_id', Auth::guard('tenant')->user()->outlet_id);
            }])
            ->get();
    }

    public function addToCart($serviceId)
    {
        $service = Service::find($serviceId);
        if (!$service) return;

        // Find pricing for the active outlet
        $outletId = Auth::guard('tenant')->user()->outlet_id;
        $priceModel = ServicePrice::where('service_id', $serviceId)
            ->where('outlet_id', $outletId)
            ->where('price_type', $this->priority)
            ->first();

        $price = $priceModel ? (float) $priceModel->price : 10000.00;

        if (isset($this->cart[$serviceId])) {
            $this->cart[$serviceId]['qty'] += 1;
        } else {
            $this->cart[$serviceId] = [
                'id' => $service->id,
                'name' => $service->name,
                'qty' => 1,
                'unit' => $service->unit,
                'price' => $price,
            ];
        }
        $this->recalculatePrices();
    }

    public function updateQty($serviceId, $qty)
    {
        $qty = (float) $qty;
        if ($qty <= 0) {
            unset($this->cart[$serviceId]);
        } else {
            $this->cart[$serviceId]['qty'] = $qty;
        }
        $this->recalculatePrices();
    }

    public function removeFromCart($serviceId)
    {
        unset($this->cart[$serviceId]);
        $this->recalculatePrices();
    }

    public function incrementQty($serviceId)
    {
        if (isset($this->cart[$serviceId])) {
            $this->cart[$serviceId]['qty'] += ($this->cart[$serviceId]['unit'] === 'kg' ? 0.5 : 1);
            $this->recalculatePrices();
        }
    }

    public function decrementQty($serviceId)
    {
        if (isset($this->cart[$serviceId])) {
            $this->cart[$serviceId]['qty'] -= ($this->cart[$serviceId]['unit'] === 'kg' ? 0.5 : 1);
            if ($this->cart[$serviceId]['qty'] <= 0) {
                unset($this->cart[$serviceId]);
            }
            $this->recalculatePrices();
        }
    }

    public function connectScale()
    {
        $this->isScaleConnected = !$this->isScaleConnected;
        if ($this->isScaleConnected) {
            $this->scaleWeight = 2.50; // Initial simulated scale weight (e.g. 2.5 kg)
        } else {
            $this->scaleWeight = 0.00;
        }
    }

    public function applyWeightFromScale($serviceId)
    {
        if ($this->isScaleConnected && isset($this->cart[$serviceId])) {
            $this->cart[$serviceId]['qty'] = $this->scaleWeight;
            $this->recalculatePrices();
            
            // Log tenant activity log
            \App\Models\Tenant\ActivityLog::create([
                'description' => 'Berat timbangan IoT otomatis diterapkan pada ' . $this->cart[$serviceId]['name'] . ': ' . $this->scaleWeight . ' ' . $this->cart[$serviceId]['unit'],
                'causer_id' => Auth::guard('tenant')->id(),
            ]);
        }
    }

    public function triggerAiScan()
    {
        $this->showAiScanModal = true;
        $this->isAiScanning = false;
    }

    public function runAiScan($photoData)
    {
        $this->isAiScanning = true;
        
        // Simulate AI classification delay
        usleep(1500000); 

        // Fetch demo kiloan & satuan services
        $kiloanService = Service::where('unit', 'kg')->first();
        $satuanService = Service::where('unit', 'pcs')->first() ?? Service::where('unit', '!=', 'kg')->first();

        $outletId = Auth::guard('tenant')->user()->outlet_id;

        // Auto add 3.5 kg Kiloan & 2 Pcs Satuan to cart as predicted by AI
        if ($kiloanService) {
            $priceObj = ServicePrice::where('service_id', $kiloanService->id)
                ->where('outlet_id', $outletId)
                ->where('price_type', $this->priority)
                ->first();
            $price = $priceObj ? (float)$priceObj->price : 10000.00;

            $this->cart[$kiloanService->id] = [
                'id' => $kiloanService->id,
                'name' => '[AI-Detected] ' . $kiloanService->name,
                'qty' => 3.5,
                'unit' => $kiloanService->unit,
                'price' => $price,
            ];
        }

        if ($satuanService) {
            $priceObj = ServicePrice::where('service_id', $satuanService->id)
                ->where('outlet_id', $outletId)
                ->where('price_type', $this->priority)
                ->first();
            $price = $priceObj ? (float)$priceObj->price : 15000.00;

            $this->cart[$satuanService->id] = [
                'id' => $satuanService->id,
                'name' => '[AI-Detected] ' . $satuanService->name,
                'qty' => 2,
                'unit' => $satuanService->unit,
                'price' => $price,
            ];
        }

        $this->showAiScanModal = false;
        $this->isAiScanning = false;
        $this->recalculatePrices();

        // Log tenant activity log
        \App\Models\Tenant\ActivityLog::create([
            'description' => 'Melakukan pemindaian AI pada drop-off pakaian (2 item terdeteksi otomatis)',
            'causer_id' => Auth::guard('tenant')->id(),
        ]);
    }

    public function updatedPriority()
    {
        // Re-fetch prices from DB based on priority type multiplier
        $outletId = Auth::guard('tenant')->user()->outlet_id;
        foreach ($this->cart as $serviceId => $item) {
            $priceModel = ServicePrice::where('service_id', $serviceId)
                ->where('outlet_id', $outletId)
                ->where('price_type', $this->priority)
                ->first();
            
            if ($priceModel) {
                $this->cart[$serviceId]['price'] = (float) $priceModel->price;
            }
        }
        $this->recalculatePrices();
    }

    public function recalculatePrices()
    {
        // For inline calculations
    }

    // Calculations accessors
    public function getSubtotalProperty(): float
    {
        $sum = 0.00;
        foreach ($this->cart as $item) {
            $sum += $item['price'] * $item['qty'];
        }
        return $sum;
    }

    public function getTaxProperty(): float
    {
        $rate = (float) \App\Models\Tenant\Setting::getValue('tax_rate', 11);
        return $this->getSubtotal() * ($rate / 100);
    }

    public function getTotalProperty(): float
    {
        return $this->getSubtotal() + $this->getTax();
    }

    public function getChangeProperty(): float
    {
        $change = $this->paidAmount - $this->getTotal();
        return $change > 0 ? $change : 0.00;
    }

    // Helper functions to bridge Livewire 4 SFC property getters
    public function getSubtotal() { return $this->getSubtotalProperty(); }
    public function getTax() { return $this->getTaxProperty(); }
    public function getTotal() { return $this->getTotalProperty(); }
    public function getChange() { return $this->getChangeProperty(); }

    public function openPayment()
    {
        if (empty($this->cart)) {
            $this->addError('cart', 'Keranjang belanja kosong.');
            return;
        }
        if (!$this->selectedCustomerId) {
            $this->addError('customer', 'Silakan pilih pelanggan terlebih dahulu.');
            return;
        }
        $this->paidAmount = $this->getTotal();
        $this->showPaymentModal = true;
    }

    public function checkout(OrderRepositoryInterface $orderRepo)
    {
        if ($this->paidAmount < 0) {
            $this->addError('payment', 'Jumlah bayar tidak valid.');
            return;
        }

        if ($this->paidAmount < $this->getTotal() && $this->paymentMethod === 'cash') {
            $this->addError('payment', 'Jumlah bayar kurang.');
            return;
        }

        // Generate invoice number
        $invoicePrefix = 'INV-' . date('ymd');
        $lastOrder = Order::where('invoice_number', 'like', $invoicePrefix . '%')->latest()->first();
        if ($lastOrder) {
            $lastNum = (int) substr($lastOrder->invoice_number, -4);
            $invoiceNumber = $invoicePrefix . str_pad((string)($lastNum + 1), 4, '0', STR_PAD_LEFT);
        } else {
            $invoiceNumber = $invoicePrefix . '0001';
        }

        $outletId = Auth::guard('tenant')->user()->outlet_id;

        // Build Order Data DTO
        $orderData = [
            'invoice_number' => $invoiceNumber,
            'customer_id' => $this->selectedCustomerId,
            'outlet_id' => $outletId,
            'created_by' => Auth::guard('tenant')->id(),
            'status' => OrderStatus::PENDING->value,
            'priority' => $this->priority,
            'subtotal' => $this->getSubtotal(),
            'tax' => $this->getTax(),
            'total' => $this->getTotal(),
            'paid_amount' => $this->paidAmount,
            'change_amount' => $this->getChange(),
            'payment_status' => $this->paidAmount >= $this->getTotal() ? 'paid' : 'partial',
            'notes' => $this->notes,
            'special_instructions' => $this->specialInstructions,
            'estimated_completion' => now()->addHours(48), // Default duration representation
        ];

        // Format items array
        $itemsData = [];
        foreach ($this->cart as $item) {
            $itemsData[] = [
                'service_id' => $item['id'],
                'service_name' => $item['name'],
                'quantity' => $item['qty'],
                'unit' => $item['unit'],
                'unit_price' => $item['price'],
                'subtotal' => $item['price'] * $item['qty'],
                'total' => $item['price'] * $item['qty'],
            ];
        }

        $order = $orderRepo->create($orderData, $itemsData);

        // Record Order Payment
        if ($this->paidAmount > 0) {
            $order->payments()->create([
                'amount' => $this->paidAmount > $this->getTotal() ? $this->getTotal() : $this->paidAmount,
                'method' => $this->paymentMethod,
                'reference_number' => $this->referenceNumber,
                'received_by' => Auth::guard('tenant')->id(),
                'paid_at' => now(),
            ]);
        }

        // Increment customer spent
        $orderRepoCustomer = app(OrderRepositoryInterface::class); // using CustomerRepo is cleaner but repository interface provides incrementSpent
        $customerRepo = app(\App\Repositories\Contracts\CustomerRepositoryInterface::class);
        $customerRepo->incrementSpent($this->selectedCustomerId, $this->getTotal());

        // Redirect to Print Receipt
        return redirect()->route('tenant.order-details', $order->id);
    }

    public function render()
    {
        return <<<'HTML'
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 h-[calc(100vh-10rem)]">
            <!-- Left Panel: Catalog Grouped by Categories -->
            <div class="lg:col-span-8 flex flex-col space-y-6 h-full overflow-hidden">
                <!-- Top Header for Search Customer -->
                <div class="bg-white border border-[#E2E7EF] p-4 rounded-2xl  flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                    <div class="flex-1 relative" x-data="{ open: true }">
                        @if(!$selectedCustomer)
                            <div class="relative">
                                <input wire:model.live.debounce.300ms="customerSearch" type="text" placeholder="Cari pelanggan berdasarkan nama/no HP..."
                                    class="w-full pl-10 pr-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/10 focus:border-[#1E3A5F] text-sm">
                                <div class="absolute left-3.5 top-3 text-[#8896A6]">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>
                            @if(strlen($customerSearch) >= 2)
                                <div x-show="open" @click.away="open = false" class="absolute left-0 right-0 mt-2 rounded-xl bg-white border border-[#E2E7EF] shadow-2xl py-1 text-sm text-[#4A5568] z-50">
                                    @forelse($customers as $c)
                                        <button wire:click="selectCustomer({{ $c->id }})" class="w-full text-left px-4 py-2.5 hover:bg-[#F8F9FC] hover:text-[#1A1D23] flex items-center justify-between">
                                            <span>{{ $c->name }}</span>
                                            <span class="text-xs text-[#8896A6]">{{ $c->phone }}</span>
                                        </button>
                                    @empty
                                        <div class="px-4 py-2.5 text-[#8896A6] text-center">Tidak ditemukan.</div>
                                    @endforelse
                                </div>
                            @endif
                        @else
                            <div class="flex items-center justify-between bg-[#1E3A5F]/5 border border-[#1E3A5F]/15 rounded-xl px-4 py-2 text-sm text-[#1E3A5F]">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-full bg-[#1E3A5F]/10 text-[#1E3A5F] flex items-center justify-center font-bold">
                                        {{ strtoupper(substr($selectedCustomer->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-[#1A1D23]">{{ $selectedCustomer->name }}</p>
                                        <p class="text-xs text-[#8896A6]">{{ $selectedCustomer->phone }}</p>
                                    </div>
                                </div>
                                <button wire:click="deselectCustomer" class="text-[#8896A6] hover:text-[#1A1D23] focus:outline-none">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        @endif
                        @error('customer') <span class="text-xs text-rose-400 mt-2 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center space-x-2">
                        <!-- AI Clothes Scanner -->
                        <button wire:click="triggerAiScan" class="px-4 py-2.5 bg-[#D4A853] hover:bg-[#E8C97A] text-white rounded-xl font-semibold text-sm transition-all flex items-center space-x-1.5 cursor-pointer shadow-lg shadow-[#D4A853]/10">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>AI Scan</span>
                        </button>

                        <!-- IoT Bluetooth Scale Status -->
                        <button wire:click="connectScale" class="px-4 py-2.5 rounded-xl border text-sm font-semibold transition-all flex items-center space-x-1.5 cursor-pointer {{ $isScaleConnected ? 'bg-emerald-500/15 text-emerald-600 border-emerald-500/30' : 'bg-[#F8F9FC] hover:bg-[#E2E7EF] text-[#8896A6] border-[#E2E7EF]' }}">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $isScaleConnected ? 'bg-emerald-400' : 'bg-slate-400' }}"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 {{ $isScaleConnected ? 'bg-emerald-500' : 'bg-slate-500' }}"></span>
                            </span>
                            <span>Timbangan: {{ $isScaleConnected ? $scaleWeight . ' kg' : 'Offline' }}</span>
                        </button>

                        <button wire:click="openCustomerModal" class="px-4 py-2.5 bg-[#F8F9FC] hover:bg-[#E2E7EF] text-[#1A1D23] rounded-xl border border-[#E2E7EF] text-sm font-semibold transition-all cursor-pointer">
                            + Pelanggan
                        </button>
                    </div>
                </div>

                @if($isScaleConnected)
                    <!-- Scale Simulator Panel -->
                    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex items-center space-x-3">
                            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wide">Simulator Timbangan IoT</span>
                            <span class="text-lg font-bold text-[#1A1D23]">{{ number_format($scaleWeight, 2) }} kg</span>
                        </div>
                        <div class="flex-1 max-w-xs flex items-center space-x-3">
                            <input type="range" min="0.5" max="25" step="0.1" wire:model.live="scaleWeight" class="w-full h-1.5 bg-[#F8F9FC] rounded-lg appearance-none cursor-pointer accent-emerald-500">
                        </div>
                        <p class="text-[10px] text-emerald-600 max-w-[200px] text-right">Gunakan slider untuk mensimulasikan berat cucian dari mesin timbangan digital.</p>
                    </div>
                @endif

                <!-- Catalog Categories Tabs -->
                <div class="flex space-x-2 overflow-x-auto pb-1">
                    @foreach($categories as $cat)
                        <button wire:click="filterCategory({{ $cat->id }})" 
                            class="px-4 py-2 text-xs font-semibold rounded-lg border transition-all shrink-0 cursor-pointer {{ $selectedCategoryId === $cat->id ? 'bg-[#1E3A5F]/5 text-[#1E3A5F] border-[#1E3A5F]/15' : 'bg-white text-[#8896A6] border-[#E2E7EF] hover:bg-[#F8F9FC] hover:text-[#1A1D23]' }}">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>

                <!-- Catalog Grid -->
                <div class="flex-1 overflow-y-auto pr-1">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @forelse($services as $srv)
                            @php
                                $priceObj = $srv->prices->where('price_type', $priority)->first();
                                $price = $priceObj ? $priceObj->price : 0;
                            @endphp
                            <div wire:click="addToCart({{ $srv->id }})" 
                                class="bg-white border border-[#E2E7EF] rounded-xl p-4 cursor-pointer hover:border-[#E2E7EF] hover:bg-[#F8F9FC] transition-all flex flex-col justify-between h-36 select-none group">
                                <div>
                                    <h4 class="text-sm font-semibold text-[#1A1D23] group-hover:text-[#1E3A5F] transition-colors leading-tight">{{ $srv->name }}</h4>
                                    <p class="text-xs text-[#8896A6] mt-1 line-clamp-2">{{ $srv->description }}</p>
                                </div>
                                <div class="flex items-center justify-between border-t border-[#E2E7EF] pt-2.5">
                                    <span class="text-xs text-[#8896A6] font-medium">per {{ $srv->unit }}</span>
                                    <span class="text-sm font-bold text-[#1A1D23]">Rp {{ number_format($price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full flex flex-col items-center justify-center py-12 text-[#8896A6]">
                                <p>Belum ada layanan dalam kategori ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Panel: Cart Summary & Checkout -->
            <div class="lg:col-span-4 bg-white border border-[#E2E7EF] rounded-2xl flex flex-col justify-between h-full overflow-hidden ">
                <!-- Cart Items List -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4">
                    <div class="flex justify-between items-center border-b border-[#E2E7EF] pb-3">
                        <h3 class="text-md font-bold text-[#1A1D23]">Keranjang</h3>
                        <span class="text-xs bg-indigo-500/10 text-[#1E3A5F] border border-[#1E3A5F]/15 px-2 py-0.5 rounded-full font-semibold">{{ count($cart) }} Item</span>
                    </div>

                    @error('cart') <div class="bg-rose-950/20 border border-rose-500/30 text-rose-400 text-xs px-3 py-2 rounded-lg">{{ $message }}</div> @enderror

                    <div class="space-y-4">
                        @forelse($cart as $item)
                            <div class="flex items-start justify-between border-b border-[#E2E7EF] pb-3 last:border-0 last:pb-0">
                                <div class="flex-1">
                                    <h5 class="text-sm font-semibold text-[#1A1D23] leading-tight">{{ $item['name'] }}</h5>
                                    <p class="text-xs text-[#8896A6] mt-0.5">Rp {{ number_format($item['price'], 0, ',', '.') }} / {{ $item['unit'] }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <!-- Scale Apply Button -->
                                    @if($isScaleConnected && $item['unit'] === 'kg')
                                        <button wire:click="applyWeightFromScale({{ $item['id'] }})" class="p-1 rounded-lg bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 hover:bg-emerald-500/25 transition-all text-[9px] font-bold flex items-center space-x-0.5 cursor-pointer" title="Gunakan berat dari timbangan IoT">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                                            </svg>
                                            <span>Ambil Berat</span>
                                        </button>
                                    @endif

                                    <!-- Plus / Minus increment decrement buttons -->
                                    <button wire:click="decrementQty({{ $item['id'] }})" class="h-6 w-6 rounded-lg bg-slate-850 border border-[#E2E7EF] text-[#8896A6] hover:bg-[#F8F9FC] hover:text-[#1A1D23] flex items-center justify-center font-bold text-xs select-none transition-all cursor-pointer">-</button>
                                    <input type="number" step="{{ $item['unit'] === 'kg' ? '0.1' : '1' }}" wire:change="updateQty({{ $item['id'] }}, $event.target.value)" value="{{ $item['qty'] }}"
                                        class="w-12 bg-[#F8F9FC] border border-[#E2E7EF] text-[#1A1D23] text-center text-xs py-1 rounded-lg focus:outline-none focus:border-[#1E3A5F]">
                                    <button wire:click="incrementQty({{ $item['id'] }})" class="h-6 w-6 rounded-lg bg-slate-850 border border-[#E2E7EF] text-[#8896A6] hover:bg-[#F8F9FC] hover:text-[#1A1D23] flex items-center justify-center font-bold text-xs select-none transition-all cursor-pointer">+</button>
                                    <button wire:click="removeFromCart({{ $item['id'] }})" class="text-[#8896A6] hover:text-rose-400 transition-colors pl-1 cursor-pointer">
                                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-12 text-[#8896A6] text-sm">
                                <p>Keranjang kosong.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Invoice Configurations (Priority, instruction, sums) -->
                <div class="border-t border-[#E2E7EF] p-6 space-y-4 bg-[#F8F9FC]">
                    <!-- Priority Selector -->
                    <div>
                        <label class="block text-xs font-semibold text-[#8896A6] uppercase tracking-wider mb-2">Prioritas Layanan</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="flex flex-col items-center justify-center p-2 border rounded-xl cursor-pointer transition-all {{ $priority === 'regular' ? 'bg-[#1E3A5F]/15 border-indigo-500 text-[#1E3A5F]' : 'bg-white border-[#E2E7EF] text-[#8896A6] hover:text-[#1A1D23]' }}">
                                <input type="radio" wire:model.live="priority" value="regular" class="sr-only">
                                <span class="text-xs font-bold">Reguler</span>
                                <span class="text-[9px] text-[#8896A6] mt-0.5">2 Hari</span>
                            </label>
                            <label class="flex flex-col items-center justify-center p-2 border rounded-xl cursor-pointer transition-all {{ $priority === 'express' ? 'bg-[#1E3A5F]/15 border-indigo-500 text-[#1E3A5F]' : 'bg-white border-[#E2E7EF] text-[#8896A6] hover:text-[#1A1D23]' }}">
                                <input type="radio" wire:model.live="priority" value="express" class="sr-only">
                                <span class="text-xs font-bold">Express</span>
                                <span class="text-[9px] text-[#8896A6] mt-0.5">1 Hari</span>
                            </label>
                            <label class="flex flex-col items-center justify-center p-2 border rounded-xl cursor-pointer transition-all {{ $priority === 'super_express' ? 'bg-[#1E3A5F]/15 border-indigo-500 text-[#1E3A5F]' : 'bg-white border-[#E2E7EF] text-[#8896A6] hover:text-[#1A1D23]' }}">
                                <input type="radio" wire:model.live="priority" value="super_express" class="sr-only">
                                <span class="text-xs font-bold">Super</span>
                                <span class="text-[9px] text-[#8896A6] mt-0.5">3-6 Jam</span>
                            </label>
                        </div>
                    </div>

                    <!-- Summary Bill -->
                    <div class="space-y-2 border-t border-[#E2E7EF] pt-4 text-sm font-medium">
                        <div class="flex justify-between text-[#8896A6]">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($this->getSubtotal(), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-[#8896A6]">
                            <span>Pajak ({{ (float) \App\Models\Tenant\Setting::getValue('tax_rate', 11) }}%)</span>
                            <span>Rp {{ number_format($this->getTax(), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-[#1A1D23] text-base font-bold border-t border-[#E2E7EF]/50 pt-2.5">
                            <span>Total Pembayaran</span>
                            <span>Rp {{ number_format($this->getTotal(), 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button wire:click="openPayment" 
                        class="w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-xl font-bold shadow-lg shadow-[#1E3A5F]/10 hover:shadow-indigo-600/20 transition-all cursor-pointer">
                        Proses Pembayaran
                    </button>
                </div>
            </div>

            <!-- Customer Creation Modal -->
            @if($showCustomerModal)
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-[#F8F9FC] backdrop-blur-sm">
                    <div class="bg-white border border-[#E2E7EF] rounded-2xl w-full max-w-md p-6 shadow-2xl space-y-6">
                        <div class="flex justify-between items-center border-b border-[#E2E7EF] pb-3">
                            <h3 class="text-lg font-bold text-[#1A1D23]">Tambah Pelanggan Baru</h3>
                            <button wire:click="closeCustomerModal" class="text-[#8896A6] hover:text-[#1A1D23]">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-[#4A5568]">Nama Lengkap</label>
                                <input wire:model="newCustomerName" type="text" class="w-full mt-1 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-lg text-sm">
                                @error('newCustomerName') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#4A5568]">No. WhatsApp</label>
                                <input wire:model="newCustomerPhone" type="text" class="w-full mt-1 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-lg text-sm" placeholder="08...">
                                @error('newCustomerPhone') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3 border-t border-[#E2E7EF] pt-4">
                            <button wire:click="closeCustomerModal" class="px-4 py-2 border border-[#E2E7EF] text-[#8896A6] hover:text-[#1A1D23] rounded-xl text-sm font-semibold">Batal</button>
                            <button wire:click="createCustomer" class="px-4 py-2 bg-[#1E3A5F] hover:bg-[#2A5082] text-white rounded-xl text-sm font-semibold shadow-lg">Simpan</button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Payment Confirmation Modal -->
            @if($showPaymentModal)
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-[#F8F9FC] backdrop-blur-sm">
                    <div class="bg-white border border-[#E2E7EF] rounded-2xl w-full max-w-md p-6 shadow-2xl space-y-6">
                        <div class="flex justify-between items-center border-b border-[#E2E7EF] pb-3">
                            <h3 class="text-lg font-bold text-[#1A1D23]">Konfirmasi Pembayaran</h3>
                            <button wire:click="closePaymentModal" class="text-[#8896A6] hover:text-[#1A1D23]">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <div class="space-y-4">
                            <div class="bg-[#F8F9FC] p-4 border border-slate-850 rounded-xl flex justify-between items-center text-sm">
                                <span class="font-medium text-[#8896A6]">Total Tagihan</span>
                                <span class="text-lg font-bold text-emerald-600">Rp {{ number_format($this->getTotal(), 0, ',', '.') }}</span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-[#4A5568]">Metode Pembayaran</label>
                                <select wire:model.live="paymentMethod" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm">
                                    <option value="cash">Tunai (Cash)</option>
                                    <option value="transfer">Bank Transfer</option>
                                    <option value="qris">QRIS (Mock)</option>
                                </select>
                            </div>

                            @if($paymentMethod === 'cash')
                                <div>
                                    <label class="block text-sm font-medium text-[#4A5568]">Uang Diterima</label>
                                    <input wire:model.live="paidAmount" type="number" class="w-full mt-1 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-lg text-sm">
                                    @error('payment') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="flex justify-between items-center text-sm font-medium border-t border-[#E2E7EF] pt-3">
                                    <span class="text-[#8896A6]">Kembalian</span>
                                    <span class="text-[#1A1D23]">Rp {{ number_format($this->getChange(), 0, ',', '.') }}</span>
                                </div>
                            @elseif($paymentMethod === 'qris')
                                <!-- Mock QR Code -->
                                <div class="flex flex-col items-center justify-center bg-[#F8F9FC] p-4 border border-slate-850 rounded-xl space-y-3">
                                    <div class="h-44 w-44 bg-white p-2 rounded-lg flex items-center justify-center shadow-lg">
                                        <!-- Renders QR block representation -->
                                        <div class="h-40 w-40 bg-[radial-gradient(#000_15%,transparent_16%),radial-gradient(#000_15%,transparent_16%)] bg-[length:16px_16px] bg-[position:0_0,8px_8px] border border-slate-300"></div>
                                    </div>
                                    <span class="text-xs text-[#8896A6]">Pindai QR di atas menggunakan dompet digital</span>
                                </div>
                            @else
                                <div>
                                    <label class="block text-sm font-medium text-[#4A5568]">No. Referensi / Bukti</label>
                                    <input wire:model="referenceNumber" type="text" placeholder="Masukkan ID transaksi" class="w-full mt-1 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-lg text-sm">
                                </div>
                            @endif
                        </div>
                        <div class="flex justify-end space-x-3 border-t border-[#E2E7EF] pt-4">
                            <button wire:click="closePaymentModal" class="px-4 py-2 border border-[#E2E7EF] text-[#8896A6] hover:text-[#1A1D23] rounded-xl text-sm font-semibold">Batal</button>
                            <button wire:click="checkout" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-sm font-bold shadow-lg shadow-emerald-600/10 cursor-pointer">Selesaikan Transaksi</button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- AI Scanner Modal -->
            @if($showAiScanModal)
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-[#F8F9FC] backdrop-blur-sm">
                    <div class="bg-white border border-[#E2E7EF] rounded-2xl w-full max-w-lg p-6 shadow-2xl space-y-6 relative overflow-hidden" x-data="{ hasPhoto: false }">
                        <!-- Laser Scanning Line Effect -->
                        @if($isAiScanning)
                            <div class="absolute inset-x-0 h-1 bg-gradient-to-r from-transparent via-purple-500 to-transparent top-0 animate-[bounce_2s_infinite] z-20"></div>
                        @endif

                        <div class="flex justify-between items-center border-b border-[#E2E7EF] pb-3">
                            <div class="flex items-center space-x-2">
                                <span class="h-2 w-2 rounded-full bg-purple-500 animate-ping"></span>
                                <h3 class="text-lg font-bold text-[#1A1D23]">AI Clothes Classifier Scanner</h3>
                            </div>
                            <button wire:click="closeAiScanModal" class="text-[#8896A6] hover:text-[#1A1D23]">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="space-y-4">
                            <!-- Mock Video Camera Viewport -->
                            <div class="relative bg-[#F8F9FC] border border-slate-850 rounded-xl overflow-hidden h-64 flex flex-col items-center justify-center">
                                @if(!$isAiScanning)
                                    <!-- Camera simulation stream -->
                                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(99,102,241,0.05)_0,transparent_100%)] opacity-80 flex flex-col items-center justify-center space-y-3">
                                        <svg class="h-12 w-12 text-slate-700 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        </svg>
                                        <span class="text-xs text-slate-600 font-semibold uppercase tracking-wider">Mencari Aliran Kamera Drop-Off...</span>
                                    </div>
                                @else
                                    <!-- Scanning overlay -->
                                    <div class="absolute inset-0 bg-purple-950/20 backdrop-blur-xs flex flex-col items-center justify-center space-y-4">
                                        <div class="h-16 w-16 border-4 border-t-purple-500 border-r-transparent border-[#E2E7EF] rounded-full animate-spin"></div>
                                        <p class="text-xs text-purple-400 font-bold tracking-wider animate-pulse">AI Sedang Mengklasifikasikan Pakaian...</p>
                                    </div>
                                @endif

                                <!-- Corner Camera Guides -->
                                <div class="absolute top-4 left-4 h-6 w-6 border-t-2 border-l-2 border-slate-600"></div>
                                <div class="absolute top-4 right-4 h-6 w-6 border-t-2 border-r-2 border-slate-600"></div>
                                <div class="absolute bottom-4 left-4 h-6 w-6 border-b-2 border-l-2 border-slate-600"></div>
                                <div class="absolute bottom-4 right-4 h-6 w-6 border-b-2 border-r-2 border-slate-600"></div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 border-t border-[#E2E7EF] pt-4">
                            <button wire:click="closeAiScanModal" class="px-4 py-2 border border-[#E2E7EF] text-[#8896A6] hover:text-[#1A1D23] rounded-xl text-sm font-semibold" :disabled="$wire.isAiScanning">Batal</button>
                            <button wire:click="runAiScan('mock_base64')" class="px-5 py-2 bg-[#D4A853] hover:bg-[#E8C97A] text-white rounded-xl text-sm font-bold shadow-lg shadow-[#D4A853]/10 cursor-pointer flex items-center space-x-2" :disabled="$wire.isAiScanning">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <span>Ambil Foto & Analisis</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        HTML;
    }
}
