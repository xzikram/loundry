<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-[#1A1D23]">Layanan & Harga</h1>
            <p class="text-sm text-[#8896A6] mt-1">Kelola jenis cucian, satuan, durasi, serta rincian tarif laundry</p>
        </div>
        <button wire:click="openCreate" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] shadow-lg shadow-[#1E3A5F]/10 cursor-pointer hover:shadow-xl transition-all">
            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Layanan
        </button>
    </div>

    <!-- Alert Success -->
    @if(session()->has('message'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl text-sm font-medium flex items-center space-x-2">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <!-- Search & Filters -->
    <div class="bg-white border border-[#E2E7EF] p-4 rounded-2xl flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 shadow-sm">
        <div class="flex-1 relative">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama layanan..."
                class="w-full pl-10 pr-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/10 focus:border-[#1E3A5F] text-sm">
            <div class="absolute left-3.5 top-3 text-[#8896A6]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>
        <select wire:model.live="filterCategoryId" class="px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/10">
            <option value="0">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Data Table -->
    <div class="bg-white border border-[#E2E7EF] rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#E2E7EF] bg-[#F8F9FC] text-[#8896A6] text-xs font-semibold uppercase tracking-wider">
                        <th class="px-6 py-4">Nama Layanan</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Satuan / Durasi</th>
                        <th class="px-6 py-4">Tarif Reguler</th>
                        <th class="px-6 py-4">Tarif Express</th>
                        <th class="px-6 py-4">Tarif Super</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E7EF] text-sm text-[#4A5568]">
                    @forelse($services as $srv)
                        @php
                            $priceReg = $srv->prices->where('price_type', 'regular')->first();
                            $priceExp = $srv->prices->where('price_type', 'express')->first();
                            $priceSuper = $srv->prices->where('price_type', 'super_express')->first();
                        @endphp
                        <tr class="hover:bg-[#F8F9FC] transition-all">
                            <td class="px-6 py-4">
                                <p class="font-bold text-[#1A1D23]">{{ $srv->name }}</p>
                                <p class="text-xs text-[#8896A6] mt-0.5 max-w-[200px] truncate">{{ $srv->description ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-[#1E3A5F]/5 text-[#1E3A5F] border border-[#1E3A5F]/10">
                                    {{ $srv->category->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-[#1A1D23] capitalize">per {{ $srv->unit }}</p>
                                <p class="text-xs text-[#8896A6] mt-0.5">{{ $srv->estimated_duration_hours }} Jam</p>
                            </td>
                            <td class="px-6 py-4 font-bold text-[#1A1D23]">
                                Rp {{ $priceReg ? number_format($priceReg->price, 0, ',', '.') : '0' }}
                                @if($priceReg && $priceReg->min_weight > 0)
                                    <span class="block text-[10px] text-[#8896A6] font-medium">Min. {{ number_format($priceReg->min_weight, 1) }} {{ $srv->unit }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-blue-600">
                                Rp {{ $priceExp ? number_format($priceExp->price, 0, ',', '.') : '0' }}
                                @if($priceExp && $priceExp->min_weight > 0)
                                    <span class="block text-[10px] text-[#8896A6] font-medium">Min. {{ number_format($priceExp->min_weight, 1) }} {{ $srv->unit }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-purple-600">
                                Rp {{ $priceSuper ? number_format($priceSuper->price, 0, ',', '.') : '0' }}
                                @if($priceSuper && $priceSuper->min_weight > 0)
                                    <span class="block text-[10px] text-[#8896A6] font-medium">Min. {{ number_format($priceSuper->min_weight, 1) }} {{ $srv->unit }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($srv->is_active)
                                    <span class="inline-flex items-center text-xs px-2.5 py-0.5 rounded-full font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200">Aktif</span>
                                @else
                                    <span class="inline-flex items-center text-xs px-2.5 py-0.5 rounded-full font-semibold bg-rose-50 text-rose-500 border border-rose-200">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <button wire:click="openEdit({{ $srv->id }})" class="px-3 py-1 text-xs bg-[#F8F9FC] hover:bg-[#E2E7EF] text-[#4A5568] font-semibold rounded-lg border border-[#E2E7EF] cursor-pointer">Edit</button>
                                <button wire:click="delete({{ $srv->id }})" wire:confirm="Yakin ingin menghapus layanan ini?" class="px-3 py-1 text-xs bg-rose-50 hover:bg-rose-100 text-rose-500 font-semibold rounded-lg border border-rose-200 cursor-pointer">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-[#8896A6]">
                                Belum ada daftar layanan terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-[#E2E7EF]">
            {{ $services->links() }}
        </div>
    </div>

    <!-- Add / Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs px-4">
            <div class="bg-white border border-[#E2E7EF] rounded-2xl w-full max-w-3xl p-6 shadow-2xl relative overflow-hidden max-h-[90vh] flex flex-col">
                
                <!-- Premium top line decoration -->
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-[#D4A853] via-[#10B981] to-[#3B82F6]"></div>

                <!-- Header -->
                <div class="flex justify-between items-center border-b border-[#E2E7EF] pb-4 shrink-0">
                    <div>
                        <h3 class="text-xl font-bold text-[#1A1D23]">{{ $editingId ? 'Edit' : 'Tambah' }} Layanan & Tarif Harga</h3>
                        <p class="text-xs text-[#8896A6] mt-0.5">Konfigurasi info item layanan beserta tarif per prioritas outlet aktif</p>
                    </div>
                    <button wire:click="closeModal" class="text-[#8896A6] hover:text-[#1A1D23] cursor-pointer p-1 rounded-lg hover:bg-slate-100 transition-all">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Body Content (Scrollable) -->
                <div class="flex-1 overflow-y-auto py-5 pr-1 space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                        
                        <!-- Left Panel: General Service Details -->
                        <div class="lg:col-span-5 space-y-4 lg:border-r lg:border-[#E2E7EF] lg:pr-6">
                            <div class="flex items-center space-x-2 border-b border-[#E2E7EF]/50 pb-2">
                                <span class="text-sm">📋</span>
                                <h4 class="text-xs font-bold text-[#1E3A5F] uppercase tracking-wider">Detail Informasi</h4>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-semibold text-[#4A5568]">Kategori Layanan</label>
                                <select wire:model="categoryId" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('categoryId') <p class="text-2xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#4A5568]">Nama Layanan</label>
                                <input wire:model="serviceName" type="text" placeholder="Contoh: Cuci Kering Jas" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all">
                                @error('serviceName') <p class="text-2xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#4A5568]">Deskripsi</label>
                                <textarea wire:model="description" rows="2" placeholder="Tulis keterangan atau spesifikasi item..." class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-[#4A5568]">Satuan</label>
                                    <select wire:model="unit" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all">
                                        <option value="kg">Kiloan (kg)</option>
                                        <option value="pcs">Satuan (pcs)</option>
                                        <option value="meter">Meter (m)</option>
                                        <option value="pasang">Pasang (psg)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-[#4A5568]">Estimasi (Jam)</label>
                                    <input wire:model="estimatedDurationHours" type="number" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all">
                                </div>
                            </div>

                            <div class="flex items-center space-x-2 pt-2">
                                <input wire:model="isActive" type="checkbox" id="is_active" class="h-4.5 w-4.5 text-[#1E3A5F] border-[#E2E7EF] rounded focus:ring-[#1E3A5F]/20 cursor-pointer">
                                <label for="is_active" class="text-xs font-bold text-[#4A5568] select-none cursor-pointer">Layanan Aktif & Tampil di POS</label>
                            </div>
                        </div>

                        <!-- Right Panel: Dynamic Thousand Separator Pricing Form -->
                        <div class="lg:col-span-7 space-y-4">
                            <div class="flex items-center space-x-2 border-b border-[#E2E7EF]/50 pb-2">
                                <span class="text-sm">💰</span>
                                <h4 class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Konfigurasi Tarif Harga</h4>
                            </div>

                            <!-- 1. Regular Pricing Card -->
                            <div class="p-4 bg-[#F8F9FC] border border-[#E2E7EF] rounded-2xl shadow-xs space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-[#1E3A5F] uppercase tracking-wide flex items-center gap-1.5">
                                        <span>🐢</span> 1. Tarif Reguler
                                    </span>
                                    <span class="text-[10px] bg-slate-200/50 text-[#4A5568] px-2 py-0.5 rounded-md font-semibold">Default</span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Custom Thousand Separator Input -->
                                    <div x-data="{
                                        raw: @entangle('priceRegular'),
                                        display: '',
                                        format(val) {
                                            if (!val) return '';
                                            let clean = val.toString().replace(/[^0-9]/g, '');
                                            return clean.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                        },
                                        updateRaw(val) {
                                            let clean = val.replace(/[^0-9]/g, '');
                                            this.raw = clean ? parseInt(clean) : 0;
                                            this.display = this.format(this.raw);
                                        }
                                    }" x-init="display = format(raw); $watch('raw', v => display = format(v))">
                                        <label class="block text-[11px] font-bold text-[#4A5568]">Harga (Rp)</label>
                                        <div class="relative mt-1 rounded-xl shadow-xs">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                <span class="text-xs font-semibold text-slate-400">Rp</span>
                                            </div>
                                            <input type="text" x-model="display" @input="updateRaw($event.target.value)" 
                                                class="w-full pl-8 pr-3 py-2 border border-[#E2E7EF] bg-white text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-[#4A5568]">Min. Order ({{ $unit }})</label>
                                        <input wire:model="minWeightRegular" type="number" step="0.1" class="w-full mt-1 px-3 py-2 border border-[#E2E7EF] bg-white text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all">
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Express Pricing Card -->
                            <div class="p-4 bg-blue-50/20 border border-blue-100 rounded-2xl shadow-xs space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-blue-700 uppercase tracking-wide flex items-center gap-1.5">
                                        <span>⚡</span> 2. Tarif Express
                                    </span>
                                    <span class="text-[10px] bg-blue-100/30 text-blue-600 px-2 py-0.5 rounded-md font-semibold">Cepat</span>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Custom Thousand Separator Input -->
                                    <div x-data="{
                                        raw: @entangle('priceExpress'),
                                        display: '',
                                        format(val) {
                                            if (!val) return '';
                                            let clean = val.toString().replace(/[^0-9]/g, '');
                                            return clean.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                        },
                                        updateRaw(val) {
                                            let clean = val.replace(/[^0-9]/g, '');
                                            this.raw = clean ? parseInt(clean) : 0;
                                            this.display = this.format(this.raw);
                                        }
                                    }" x-init="display = format(raw); $watch('raw', v => display = format(v))">
                                        <label class="block text-[11px] font-bold text-[#4A5568]">Harga (Rp)</label>
                                        <div class="relative mt-1 rounded-xl shadow-xs">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                <span class="text-xs font-semibold text-slate-400">Rp</span>
                                            </div>
                                            <input type="text" x-model="display" @input="updateRaw($event.target.value)" 
                                                class="w-full pl-8 pr-3 py-2 border border-blue-200/40 bg-white text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-[#4A5568]">Min. Order ({{ $unit }})</label>
                                        <input wire:model="minWeightExpress" type="number" step="0.1" class="w-full mt-1 px-3 py-2 border border-blue-200/40 bg-white text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Super Express Pricing Card -->
                            <div class="p-4 bg-purple-50/20 border border-purple-100 rounded-2xl shadow-xs space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-purple-700 uppercase tracking-wide flex items-center gap-1.5">
                                        <span>🚀</span> 3. Tarif Super Express
                                    </span>
                                    <span class="text-[10px] bg-purple-100/30 text-purple-600 px-2 py-0.5 rounded-md font-semibold">Kilat</span>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Custom Thousand Separator Input -->
                                    <div x-data="{
                                        raw: @entangle('priceSuperExpress'),
                                        display: '',
                                        format(val) {
                                            if (!val) return '';
                                            let clean = val.toString().replace(/[^0-9]/g, '');
                                            return clean.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                        },
                                        updateRaw(val) {
                                            let clean = val.replace(/[^0-9]/g, '');
                                            this.raw = clean ? parseInt(clean) : 0;
                                            this.display = this.format(this.raw);
                                        }
                                    }" x-init="display = format(raw); $watch('raw', v => display = format(v))">
                                        <label class="block text-[11px] font-bold text-[#4A5568]">Harga (Rp)</label>
                                        <div class="relative mt-1 rounded-xl shadow-xs">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                <span class="text-xs font-semibold text-slate-400">Rp</span>
                                            </div>
                                            <input type="text" x-model="display" @input="updateRaw($event.target.value)" 
                                                class="w-full pl-8 pr-3 py-2 border border-purple-200/40 bg-white text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-[#4A5568]">Min. Order ({{ $unit }})</label>
                                        <input wire:model="minWeightSuperExpress" type="number" step="0.1" class="w-full mt-1 px-3 py-2 border border-purple-200/40 bg-white text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Footer (Sticky bottom) -->
                <div class="flex justify-end space-x-3 border-t border-[#E2E7EF] pt-4 shrink-0">
                    <button wire:click="closeModal" class="px-5 py-2.5 border border-[#E2E7EF] text-[#4A5568] hover:text-[#1A1D23] rounded-xl text-sm font-semibold hover:bg-[#F8F9FC] cursor-pointer transition-all">Batal</button>
                    <button wire:click="save" class="px-6 py-2.5 bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] text-white rounded-xl text-sm font-bold shadow-lg shadow-[#1E3A5F]/20 hover:shadow-[#1E3A5F]/35 hover:scale-[1.01] transition-all cursor-pointer">Simpan Layanan</button>
                </div>

            </div>
        </div>
    @endif
</div>
