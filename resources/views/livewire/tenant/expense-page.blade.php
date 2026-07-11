<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-[#1A1D23]">Pengeluaran</h1>
            <p class="text-sm text-[#8896A6] mt-1">Pencatatan biaya operasional dan pengeluaran outlet</p>
        </div>
        <button wire:click="openCreate" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] shadow-lg shadow-[#1E3A5F]/10 cursor-pointer hover:shadow-xl transition-all">
            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Catat Pengeluaran
        </button>
    </div>

    <!-- Summary Card -->
    <div class="bg-gradient-to-br from-[#1E3A5F] to-[#12243d] border border-[#1E3A5F]/20 rounded-2xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-lg shadow-[#1E3A5F]/5">
        <div>
            <p class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Total Pengeluaran Bulan Ini</p>
            <h3 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-[#E8C97A] to-[#D4A853] mt-1.5">Rp {{ number_format($totalMonth, 0, ',', '.') }}</h3>
        </div>
        <input wire:model.live="filterMonth" type="month" class="px-4 py-2.5 border border-white/10 bg-white/5 rounded-xl text-sm text-[#E8C97A] font-bold focus:outline-none focus:ring-2 focus:ring-[#E8C97A]/25 cursor-pointer">
    </div>

    <!-- Data Table -->
    <div class="bg-white border border-[#E2E7EF] rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
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
                        <tr class="hover:bg-[#F8F9FC] transition-all">
                            <td class="px-6 py-4 text-[#8896A6] font-medium">{{ \Carbon\Carbon::parse($exp->expense_date)->format('d M Y') }}</td>
                            <td class="px-6 py-4 font-bold text-[#1A1D23]">{{ $exp->description }}</td>
                            <td class="px-6 py-4">
                                <span class="text-[11px] px-2.5 py-0.5 rounded-full bg-[#1E3A5F]/5 text-[#1E3A5F] border border-[#1E3A5F]/10 font-bold capitalize">
                                    {{ $exp->category->name ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-rose-500">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <button wire:click="delete({{ $exp->id }})" wire:confirm="Yakin hapus pengeluaran ini?" class="px-3 py-1 text-xs bg-rose-50 hover:bg-rose-100 text-rose-500 font-semibold rounded-lg border border-rose-200 transition-all cursor-pointer">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-[#8896A6]">Belum ada pengeluaran tercatat untuk bulan ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-[#E2E7EF]">{{ $expenses->links() }}</div>
    </div>

    <!-- Catat Pengeluaran Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs px-4">
            <div class="bg-white border border-[#E2E7EF] rounded-2xl w-full max-w-md p-6 shadow-2xl space-y-5 relative overflow-hidden">
                <!-- Top Accent Line -->
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-[#D4A853] via-[#10B981] to-[#3B82F6]"></div>

                <div class="flex justify-between items-center border-b border-[#E2E7EF] pb-3">
                    <h3 class="text-lg font-bold text-[#1A1D23]">Catat Biaya Pengeluaran</h3>
                    <button wire:click="closeModal" class="text-[#8896A6] hover:text-[#1A1D23] cursor-pointer p-1 rounded-lg hover:bg-slate-100 transition-all">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568]">Deskripsi Pengeluaran</label>
                        <input wire:model="description" type="text" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all" placeholder="Contoh: Beli Sabun, Bayar Listrik Outlet, Makan Staf">
                        @error('description') <p class="text-2xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Custom Thousand Separator Input -->
                        <div x-data="{
                            raw: @entangle('amount'),
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
                            <label class="block text-xs font-semibold text-[#4A5568]">Jumlah (Rp)</label>
                            <div class="relative mt-1 rounded-xl shadow-xs">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-xs font-semibold text-slate-400">Rp</span>
                                </div>
                                <input type="text" x-model="display" @input="updateRaw($event.target.value)" 
                                    class="w-full pl-8 pr-3 py-2.5 border border-[#E2E7EF] bg-white text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all">
                            </div>
                            @error('amount') <p class="text-2xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4A5568]">Tanggal</label>
                            <input wire:model="expenseDate" type="date" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all cursor-pointer">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568]">Kategori Pengeluaran</label>
                        <select wire:model="categoryId" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
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
