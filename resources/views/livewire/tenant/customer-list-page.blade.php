<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-[#1A1D23]">Pelanggan</h1>
            <p class="text-sm text-[#8896A6] mt-1">Database pelanggan dan riwayat transaksi</p>
        </div>
        <button wire:click="openCreate" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] shadow-lg shadow-[#1E3A5F]/10 cursor-pointer hover:shadow-xl transition-all">
            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Pelanggan
        </button>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white border border-[#E2E7EF] p-4 rounded-2xl shadow-sm">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau no HP..."
            class="w-full px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/10 focus:border-[#1E3A5F] transition-all">
    </div>

    <!-- Data Table -->
    <div class="bg-white border border-[#E2E7EF] rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#E2E7EF] bg-[#F8F9FC] text-[#8896A6] text-xs font-semibold uppercase tracking-wider">
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Telepon</th>
                        <th class="px-6 py-4">Total Order</th>
                        <th class="px-6 py-4">Total Spending</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E7EF] text-sm text-[#4A5568]">
                    @forelse($customers as $c)
                        <tr class="hover:bg-[#F8F9FC] transition-all">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="h-9 w-9 rounded-full bg-[#1E3A5F]/5 text-[#1E3A5F] flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($c->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-[#1A1D23]">{{ $c->name }}</p>
                                        <p class="text-xs text-[#8896A6]">{{ $c->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-[#1A1D23]">{{ $c->phone }}</td>
                            <td class="px-6 py-4 font-semibold">{{ $c->orders_count ?? $c->orders()->count() }}</td>
                            <td class="px-6 py-4 font-bold text-[#1A1D23]">Rp {{ number_format($c->total_spent ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <button wire:click="openEdit({{ $c->id }})" class="px-3 py-1 text-xs bg-[#F8F9FC] hover:bg-[#E2E7EF] text-[#4A5568] font-semibold rounded-lg border border-[#E2E7EF] transition-all cursor-pointer">Edit</button>
                                <button wire:click="delete({{ $c->id }})" wire:confirm="Yakin hapus pelanggan ini?" class="px-3 py-1 text-xs bg-rose-50 hover:bg-rose-100 text-rose-500 font-semibold rounded-lg border border-rose-200 transition-all cursor-pointer">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-[#8896A6]">Belum ada pelanggan terdaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-[#E2E7EF]">{{ $customers->links() }}</div>
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs px-4">
            <div class="bg-white border border-[#E2E7EF] rounded-2xl w-full max-w-md p-6 shadow-2xl space-y-5 relative overflow-hidden">
                <!-- Top Accent Line -->
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-[#D4A853] via-[#10B981] to-[#3B82F6]"></div>

                <div class="flex justify-between items-center border-b border-[#E2E7EF] pb-3">
                    <h3 class="text-lg font-bold text-[#1A1D23]">Tambah Pelanggan Baru</h3>
                    <button wire:click="closeModal" class="text-[#8896A6] hover:text-[#1A1D23] cursor-pointer p-1 rounded-lg hover:bg-slate-100 transition-all">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568]">Nama Lengkap</label>
                        <input wire:model="name" type="text" placeholder="Contoh: Budi Santoso" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all">
                        @error('name') <p class="text-2xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568]">No. WhatsApp</label>
                        <input wire:model="phone" type="text" placeholder="Contoh: 08123456789" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all">
                        @error('phone') <p class="text-2xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568]">Email</label>
                        <input wire:model="email" type="email" placeholder="Contoh: budi@gmail.com (Opsional)" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568]">Alamat Lengkap</label>
                        <textarea wire:model="address" rows="2" placeholder="Masukkan alamat lengkap tinggal pelanggan..." class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all"></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 border-t border-[#E2E7EF] pt-4">
                    <button wire:click="closeModal" class="px-5 py-2.5 border border-[#E2E7EF] text-[#4A5568] hover:text-[#1A1D23] rounded-xl text-sm font-semibold hover:bg-[#F8F9FC] cursor-pointer transition-all">Batal</button>
                    <button wire:click="save" class="px-6 py-2.5 bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] text-white rounded-xl text-sm font-bold shadow-lg shadow-[#1E3A5F]/20 hover:shadow-[#1E3A5F]/35 hover:scale-[1.01] transition-all cursor-pointer">Simpan</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Modal -->
    @if($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs px-4">
            <div class="bg-white border border-[#E2E7EF] rounded-2xl w-full max-w-md p-6 shadow-2xl space-y-5 relative overflow-hidden">
                <!-- Top Accent Line -->
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-[#D4A853] via-[#10B981] to-[#3B82F6]"></div>

                <div class="flex justify-between items-center border-b border-[#E2E7EF] pb-3">
                    <h3 class="text-lg font-bold text-[#1A1D23]">Edit Informasi Pelanggan</h3>
                    <button wire:click="closeModal" class="text-[#8896A6] hover:text-[#1A1D23] cursor-pointer p-1 rounded-lg hover:bg-slate-100 transition-all">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568]">Nama Lengkap</label>
                        <input wire:model="name" type="text" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all">
                        @error('name') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568]">No. WhatsApp</label>
                        <input wire:model="phone" type="text" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all">
                        @error('phone') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568]">Email</label>
                        <input wire:model="email" type="email" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568]">Alamat Lengkap</label>
                        <textarea wire:model="address" rows="2" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all"></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 border-t border-[#E2E7EF] pt-4">
                    <button wire:click="closeModal" class="px-5 py-2.5 border border-[#E2E7EF] text-[#4A5568] hover:text-[#1A1D23] rounded-xl text-sm font-semibold hover:bg-[#F8F9FC] cursor-pointer transition-all">Batal</button>
                    <button wire:click="update" class="px-6 py-2.5 bg-[#1E3A5F] hover:bg-[#2A5082] text-white rounded-xl text-sm font-bold shadow-lg shadow-[#1E3A5F]/20 hover:shadow-[#1E3A5F]/35 hover:scale-[1.01] transition-all cursor-pointer">Update</button>
                </div>
            </div>
        </div>
    @endif
</div>
