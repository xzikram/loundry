<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-[#1A1D23]">Pengaturan</h1>
            <p class="text-sm text-[#8896A6] mt-1">Pencatatan biaya operasional</p>
        </div>
        <button wire:click="openCreate" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] shadow-lg shadow-[#1E3A5F]/10 cursor-pointer">
            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Catat Pengeluaran
        </button>
    </div>

    <!-- Summary Card -->
    <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-[#8896A6] uppercase tracking-wider">Total Pengeluaran Bulan Ini</p>
            <h3 class="text-2xl font-bold text-rose-500 mt-1">Rp {{ number_format($totalMonth, 0, ',', '.') }}</h3>
        </div>
        <input wire:model.live="filterMonth" type="month" class="px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] rounded-xl text-sm text-[#1A1D23]">
    </div>

    <div class="bg-white border border-[#E2E7EF] rounded-2xl overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-[#E2E7EF] bg-[#F8F9FC] text-[#8896A6] text-xs font-semibold uppercase tracking-wider">
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Deskripsi</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4 text-right">Jumlah</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E2E7EF] text-sm text-[#4A5568]">
                @forelse($expenses as $exp)
                    <tr class="hover:bg-[#F8F9FC]">
                        <td class="px-6 py-4 text-[#8896A6]">{{ \Carbon\Carbon::parse($exp->expense_date)->format('d M Y') }}</td>
                        <td class="px-6 py-4 font-semibold text-[#1A1D23]">{{ $exp->description }}</td>
                        <td class="px-6 py-4"><span class="text-xs px-2 py-0.5 rounded-full bg-[#F8F9FC] border border-[#E2E7EF] font-medium">{{ $exp->category->name ?? 'Umum' }}</span></td>
                        <td class="px-6 py-4 text-right font-bold text-rose-500">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="delete({{ $exp->id }})" wire:confirm="Yakin hapus?" class="px-3 py-1 text-xs bg-rose-50 text-rose-500 rounded-lg border border-rose-200 cursor-pointer">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-[#8896A6]">Belum ada pengeluaran bulan ini.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-[#E2E7EF]">{{ $expenses->links() }}</div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 backdrop-blur-sm">
            <div class="bg-white border border-[#E2E7EF] rounded-2xl w-full max-w-md p-6 shadow-2xl space-y-5">
                <div class="flex justify-between items-center border-b border-[#E2E7EF] pb-3">
                    <h3 class="text-lg font-bold text-[#1A1D23]">Catat Pengeluaran</h3>
                    <button @click="$wire.showModal = false" class="text-[#8896A6] hover:text-[#1A1D23]"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#4A5568]">Deskripsi</label>
                        <input wire:model="description" type="text" class="w-full mt-1 px-4 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm" placeholder="Beli sabun cair, bayar listrik, dsb">
                        @error('description') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[#4A5568]">Jumlah (Rp)</label>
                            <input wire:model="amount" type="number" class="w-full mt-1 px-4 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#4A5568]">Tanggal</label>
                            <input wire:model="expenseDate" type="date" class="w-full mt-1 px-4 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#4A5568]">Kategori</label>
                        <select wire:model="categoryId" class="w-full mt-1 px-4 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 border-t border-[#E2E7EF] pt-4">
                    <button @click="$wire.showModal = false" class="px-4 py-2 border border-[#E2E7EF] text-[#4A5568] rounded-xl text-sm font-semibold">Batal</button>
                    <button wire:click="save" class="px-4 py-2 bg-[#1E3A5F] text-white rounded-xl text-sm font-semibold shadow-lg cursor-pointer">Simpan</button>
                </div>
            </div>
        </div>
    @endif
</div>
