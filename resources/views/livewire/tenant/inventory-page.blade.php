<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-[#1A1D23]">Inventaris</h1>
            <p class="text-sm text-[#8896A6] mt-1">Stok bahan baku dan perlengkapan</p>
        </div>
        <button wire:click="openCreate" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] shadow-lg shadow-[#1E3A5F]/10 cursor-pointer">
            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Item
        </button>
    </div>

    <div class="bg-white border border-[#E2E7EF] p-4 rounded-2xl">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari item inventaris..."
            class="w-full px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/10 focus:border-[#1E3A5F]">
    </div>

    <div class="bg-white border border-[#E2E7EF] rounded-2xl overflow-hidden">
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
                            <p class="font-semibold text-[#1A1D23]">{{ $item->name }}</p>
                            <p class="text-xs text-[#8896A6]">{{ $item->supplier ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 font-bold text-[#1A1D23]">{{ number_format($item->current_stock, 1) }} {{ $item->unit }}</td>
                        <td class="px-6 py-4">{{ number_format($item->min_stock, 1) }} {{ $item->unit }}</td>
                        <td class="px-6 py-4">
                            @if($item->current_stock <= $item->min_stock)
                                <span class="inline-flex items-center text-xs px-2.5 py-0.5 rounded-full font-semibold bg-rose-50 text-rose-500 border border-rose-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500 mr-1.5 animate-pulse"></span>Low Stock
                                </span>
                            @else
                                <span class="text-xs px-2.5 py-0.5 rounded-full font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200">Aman</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">Rp {{ number_format($item->price_per_unit ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button wire:click="openEdit({{ $item->id }})" class="px-3 py-1 text-xs bg-[#F8F9FC] hover:bg-[#E2E7EF] text-[#4A5568] font-semibold rounded-lg border border-[#E2E7EF] cursor-pointer">Edit</button>
                            <button wire:click="delete({{ $item->id }})" wire:confirm="Yakin hapus item ini?" class="px-3 py-1 text-xs bg-rose-50 hover:bg-rose-100 text-rose-500 font-semibold rounded-lg border border-rose-200 cursor-pointer">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-[#8896A6]">Belum ada item inventaris.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-[#E2E7EF]">{{ $items->links() }}</div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 backdrop-blur-sm">
            <div class="bg-white border border-[#E2E7EF] rounded-2xl w-full max-w-md p-6 shadow-2xl space-y-5">
                <div class="flex justify-between items-center border-b border-[#E2E7EF] pb-3">
                    <h3 class="text-lg font-bold text-[#1A1D23]">{{ $editingId ? 'Edit' : 'Tambah' }} Item</h3>
                    <button wire:click="closeModal" class="text-[#8896A6] hover:text-[#1A1D23]"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#4A5568]">Nama Item</label>
                        <input wire:model="itemName" type="text" class="w-full mt-1 px-4 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm" placeholder="Sabun Cair, Parfum, dsb">
                        @error('itemName') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[#4A5568]">Stok Saat Ini</label>
                            <input wire:model="currentStock" type="number" step="0.1" class="w-full mt-1 px-4 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#4A5568]">Satuan</label>
                            <select wire:model="unit" class="w-full mt-1 px-4 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm">
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
                            <label class="block text-sm font-medium text-[#4A5568]">Min. Stok (Alert)</label>
                            <input wire:model="minStock" type="number" step="0.1" class="w-full mt-1 px-4 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#4A5568]">Harga/Unit</label>
                            <input wire:model="pricePerUnit" type="number" class="w-full mt-1 px-4 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#4A5568]">Supplier</label>
                        <input wire:model="supplier" type="text" class="w-full mt-1 px-4 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm" placeholder="Opsional">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 border-t border-[#E2E7EF] pt-4">
                    <button wire:click="closeModal" class="px-4 py-2 border border-[#E2E7EF] text-[#4A5568] rounded-xl text-sm font-semibold">Batal</button>
                    <button wire:click="save" class="px-4 py-2 bg-[#1E3A5F] text-white rounded-xl text-sm font-semibold shadow-lg cursor-pointer">Simpan</button>
                </div>
            </div>
        </div>
    @endif
</div>
