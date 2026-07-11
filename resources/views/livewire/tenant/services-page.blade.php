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
    <div class="bg-white border border-[#E2E7EF] p-4 rounded-2xl flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
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
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm px-4">
            <div class="bg-white border border-[#E2E7EF] rounded-2xl w-full max-w-2xl p-6 shadow-2xl space-y-5 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center border-b border-[#E2E7EF] pb-3">
                    <h3 class="text-lg font-bold text-[#1A1D23]">{{ $editingId ? 'Edit' : 'Tambah' }} Layanan & Tarif Harga</h3>
                    <button wire:click="closeModal" class="text-[#8896A6] hover:text-[#1A1D23] cursor-pointer">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- General Service Details -->
                    <div class="space-y-4 md:border-r md:border-[#E2E7EF] md:pr-4">
                        <h4 class="text-xs font-bold text-[#1E3A5F] uppercase tracking-wider border-b border-[#E2E7EF]/50 pb-1">Detail Layanan</h4>
                        
                        <div>
                            <label class="block text-xs font-medium text-[#4A5568]">Kategori Layanan</label>
                            <select wire:model="categoryId" class="w-full mt-1 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:border-[#1E3A5F]">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('categoryId') <p class="text-2xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-[#4A5568]">Nama Layanan</label>
                            <input wire:model="serviceName" type="text" placeholder="Contoh: Cuci Kering Jas" class="w-full mt-1 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:border-[#1E3A5F]">
                            @error('serviceName') <p class="text-2xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-[#4A5568]">Deskripsi Singkat</label>
                            <textarea wire:model="description" rows="2" placeholder="Tulis rincian atau keterangan item..." class="w-full mt-1 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:border-[#1E3A5F]"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-[#4A5568]">Satuan Hitung</label>
                                <select wire:model="unit" class="w-full mt-1 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:border-[#1E3A5F]">
                                    <option value="kg">Kiloan (kg)</option>
                                    <option value="pcs">Satuan (pcs)</option>
                                    <option value="meter">Meter (m)</option>
                                    <option value="pasang">Pasang (psg)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-[#4A5568]">Durasi (Jam)</label>
                                <input wire:model="estimatedDurationHours" type="number" class="w-full mt-1 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:border-[#1E3A5F]">
                            </div>
                        </div>

                        <div class="flex items-center space-x-2 pt-1">
                            <input wire:model="isActive" type="checkbox" id="is_active" class="h-4 w-4 text-[#1E3A5F] border-[#E2E7EF] rounded focus:ring-[#1E3A5F]/20">
                            <label for="is_active" class="text-xs font-medium text-[#4A5568] select-none">Layanan Aktif & Tampil di POS</label>
                        </div>
                    </div>

                    <!-- Pricing Info -->
                    <div class="space-y-4">
                        <h4 class="text-xs font-bold text-emerald-600 uppercase tracking-wider border-b border-[#E2E7EF]/50 pb-1">Konfigurasi Tarif Harga</h4>

                        <!-- Regular Pricing -->
                        <div class="p-3 bg-[#F8F9FC] border border-[#E2E7EF] rounded-xl space-y-2">
                            <span class="text-[10px] font-bold text-[#1A1D23] uppercase tracking-wide">1. Tarif Reguler</span>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[10px] text-[#8896A6]">Harga (Rp)</label>
                                    <input wire:model="priceRegular" type="number" class="w-full mt-0.5 px-3 py-1.5 border border-[#E2E7EF] bg-white text-[#1A1D23] rounded-lg text-xs focus:outline-none focus:border-[#1E3A5F]">
                                </div>
                                <div>
                                    <label class="block text-[10px] text-[#8896A6]">Min. Order ({{ $unit }})</label>
                                    <input wire:model="minWeightRegular" type="number" step="0.1" class="w-full mt-0.5 px-3 py-1.5 border border-[#E2E7EF] bg-white text-[#1A1D23] rounded-lg text-xs focus:outline-none focus:border-[#1E3A5F]">
                                </div>
                            </div>
                        </div>

                        <!-- Express Pricing -->
                        <div class="p-3 bg-blue-50/30 border border-blue-100 rounded-xl space-y-2">
                            <span class="text-[10px] font-bold text-blue-700 uppercase tracking-wide">2. Tarif Express</span>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[10px] text-blue-500/80">Harga (Rp)</label>
                                    <input wire:model="priceExpress" type="number" class="w-full mt-0.5 px-3 py-1.5 border border-blue-200/50 bg-white text-[#1A1D23] rounded-lg text-xs focus:outline-none focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-[10px] text-blue-500/80">Min. Order ({{ $unit }})</label>
                                    <input wire:model="minWeightExpress" type="number" step="0.1" class="w-full mt-0.5 px-3 py-1.5 border border-blue-200/50 bg-white text-[#1A1D23] rounded-lg text-xs focus:outline-none focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Super Express Pricing -->
                        <div class="p-3 bg-purple-50/30 border border-purple-100 rounded-xl space-y-2">
                            <span class="text-[10px] font-bold text-purple-700 uppercase tracking-wide">3. Tarif Super Express</span>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[10px] text-purple-500/80">Harga (Rp)</label>
                                    <input wire:model="priceSuperExpress" type="number" class="w-full mt-0.5 px-3 py-1.5 border border-purple-200/50 bg-white text-[#1A1D23] rounded-lg text-xs focus:outline-none focus:border-purple-500">
                                </div>
                                <div>
                                    <label class="block text-[10px] text-purple-500/80">Min. Order ({{ $unit }})</label>
                                    <input wire:model="minWeightSuperExpress" type="number" step="0.1" class="w-full mt-0.5 px-3 py-1.5 border border-purple-200/50 bg-white text-[#1A1D23] rounded-lg text-xs focus:outline-none focus:border-purple-500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 border-t border-[#E2E7EF] pt-4">
                    <button wire:click="closeModal" class="px-4 py-2 border border-[#E2E7EF] text-[#4A5568] rounded-xl text-sm font-semibold hover:bg-[#F8F9FC] cursor-pointer">Batal</button>
                    <button wire:click="save" class="px-5 py-2 bg-[#1E3A5F] hover:bg-[#2A5082] text-white rounded-xl text-sm font-bold shadow-lg shadow-[#1E3A5F]/10 cursor-pointer">Simpan Layanan</button>
                </div>
            </div>
        </div>
    @endif
</div>
