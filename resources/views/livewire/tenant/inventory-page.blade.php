<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-[#1A1D23]">Inventaris</h1>
            <p class="text-sm text-[#8896A6] mt-1">Stok bahan baku dan perlengkapan operasional outlet</p>
        </div>
        <button wire:click="openCreate" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] shadow-lg shadow-[#1E3A5F]/10 cursor-pointer hover:shadow-xl transition-all">
            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Item
        </button>
    </div>

    <!-- Search Bar -->
    <div class="bg-white border border-[#E2E7EF] p-4 rounded-2xl shadow-sm">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari item inventaris..."
            class="w-full px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/10 focus:border-[#1E3A5F] transition-all">
    </div>

    <!-- Data Table -->
    <div class="bg-white border border-[#E2E7EF] rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#E2E7EF] bg-[#F8F9FC] text-[#8896A6] text-xs font-semibold uppercase tracking-wider">
                        <th class="px-6 py-4">Item</th>
                        <th class="px-6 py-4">Stok</th>
                        <th class="px-6 py-4">Min. Stok</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Harga/Unit</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E7EF] text-sm text-[#4A5568]">
                    @forelse($items as $item)
                        <tr class="hover:bg-[#F8F9FC] transition-all">
                            <td class="px-6 py-4">
                                <p class="font-bold text-[#1A1D23]">{{ $item->name }}</p>
                                <p class="text-xs text-[#8896A6] mt-0.5">{{ $item->supplier ?? 'Tanpa Supplier' }}</p>
                            </td>
                            <td class="px-6 py-4 font-bold text-[#1A1D23]">{{ number_format($item->current_stock, 1) }} {{ $item->unit }}</td>
                            <td class="px-6 py-4">{{ number_format($item->min_stock, 1) }} {{ $item->unit }}</td>
                            <td class="px-6 py-4">
                                @if($item->current_stock <= $item->min_stock)
                                    <span class="inline-flex items-center text-xs px-2.5 py-0.5 rounded-full font-semibold bg-rose-50 text-rose-500 border border-rose-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500 mr-1.5 animate-pulse"></span>Low Stock
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-xs px-2.5 py-0.5 rounded-full font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200">Aman</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-[#1A1D23]">Rp {{ number_format($item->price_per_unit ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <button wire:click="openEdit({{ $item->id }})" class="px-3 py-1 text-xs bg-[#F8F9FC] hover:bg-[#E2E7EF] text-[#4A5568] font-semibold rounded-lg border border-[#E2E7EF] cursor-pointer">Edit</button>
                                <button wire:click="delete({{ $item->id }})" wire:confirm="Yakin hapus item ini?" class="px-3 py-1 text-xs bg-rose-50 hover:bg-rose-100 text-rose-500 font-semibold rounded-lg border border-rose-200 cursor-pointer">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-[#8896A6]">Belum ada item inventaris.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-[#E2E7EF]">{{ $items->links() }}</div>
    </div>

    <!-- Add / Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs px-4">
            <div class="bg-white border border-[#E2E7EF] rounded-2xl w-full max-w-md p-6 shadow-2xl space-y-5 relative overflow-hidden">
                <!-- Top Accent Line -->
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-[#D4A853] via-[#10B981] to-[#3B82F6]"></div>

                <div class="flex justify-between items-center border-b border-[#E2E7EF] pb-3">
                    <h3 class="text-lg font-bold text-[#1A1D23]">{{ $editingId ? 'Edit' : 'Tambah' }} Item Inventaris</h3>
                    <button wire:click="closeModal" class="text-[#8896A6] hover:text-[#1A1D23] cursor-pointer p-1 rounded-lg hover:bg-slate-100 transition-all">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568]">Nama Item</label>
                        <input wire:model="itemName" type="text" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all" placeholder="Contoh: Sabun Cair, Pewangi, Plastik, dsb">
                        @error('itemName') <p class="text-2xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#4A5568]">Stok Saat Ini</label>
                            <input wire:model="currentStock" type="number" step="0.1" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A5568]">Satuan</label>
                            <select wire:model="unit" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all">
                                <option value="liter">Liter</option>
                                <option value="kg">Kg</option>
                                <option value="pcs">Pcs</option>
                                <option value="pack">Pack</option>
                                <option value="botol">Botol</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#4A5568]">Min. Stok (Alert)</label>
                            <input wire:model="minStock" type="number" step="0.1" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all">
                        </div>
                        
                        <!-- Custom Thousand Separator Input -->
                        <div x-data="{
                            raw: @entangle('pricePerUnit'),
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
                            <label class="block text-xs font-semibold text-[#4A5568]">Harga/Unit</label>
                            <div class="relative mt-1 rounded-xl shadow-xs">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-xs font-semibold text-slate-400">Rp</span>
                                </div>
                                <input type="text" x-model="display" @input="updateRaw($event.target.value)" 
                                    class="w-full pl-8 pr-3 py-2.5 border border-[#E2E7EF] bg-white text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568]">Nama Supplier</label>
                        <input wire:model="supplier" type="text" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all" placeholder="Contoh: PT. Makmur Jaya (Opsional)">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 border-t border-[#E2E7EF] pt-4">
                    <button wire:click="closeModal" class="px-5 py-2.5 border border-[#E2E7EF] text-[#4A5568] hover:text-[#1A1D23] rounded-xl text-sm font-semibold hover:bg-[#F8F9FC] cursor-pointer transition-all">Batal</button>
                    <button wire:click="save" class="px-6 py-2.5 bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] text-white rounded-xl text-sm font-bold shadow-lg shadow-[#1E3A5F]/20 hover:shadow-[#1E3A5F]/35 hover:scale-[1.01] transition-all cursor-pointer">Simpan</button>
                </div>
            </div>
        </div>
    @endif
</div>
