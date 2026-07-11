<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-[#1A1D23]">Staf</h1>
            <p class="text-sm text-[#8896A6] mt-1">Kelola pengguna, wewenang staf, dan hak akses aplikasi</p>
        </div>
        <button wire:click="openCreate" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] shadow-lg shadow-[#1E3A5F]/10 cursor-pointer hover:shadow-xl transition-all">
            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Staf
        </button>
    </div>

    <!-- Data Table -->
    <div class="bg-white border border-[#E2E7EF] rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#E2E7EF] bg-[#F8F9FC] text-[#8896A6] text-xs font-semibold uppercase tracking-wider">
                        <th class="px-6 py-4">Staf</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E7EF] text-sm text-[#4A5568]">
                    @forelse($staff as $s)
                        <tr class="hover:bg-[#F8F9FC] transition-all">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-[#1E3A5F] to-[#2A5082] text-white flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($s->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-[#1A1D23]">{{ $s->name }}</p>
                                        <p class="text-xs text-[#8896A6] mt-0.5">{{ $s->phone ?? 'Tidak ada nomor telepon' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-[#1A1D23]">{{ $s->email }}</td>
                            <td class="px-6 py-4">
                                <span class="text-[11px] px-2.5 py-0.5 rounded-full bg-[#1E3A5F]/5 text-[#1E3A5F] border border-[#1E3A5F]/10 font-bold capitalize">
                                    {{ $s->role->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($s->is_active)
                                    <span class="inline-flex items-center text-xs px-2.5 py-0.5 rounded-full font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200">Aktif</span>
                                @else
                                    <span class="inline-flex items-center text-xs px-2.5 py-0.5 rounded-full font-semibold bg-rose-50 text-rose-500 border border-rose-200">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <button wire:click="openEdit({{ $s->id }})" class="px-3 py-1 text-xs bg-[#F8F9FC] hover:bg-[#E2E7EF] text-[#4A5568] font-semibold rounded-lg border border-[#E2E7EF] cursor-pointer">Edit</button>
                                <button wire:click="toggleActive({{ $s->id }})" class="px-3 py-1 text-xs {{ $s->is_active ? 'bg-amber-50 text-amber-600 border-amber-200 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-600 border-emerald-200 hover:bg-emerald-100' }} font-semibold rounded-lg border cursor-pointer transition-all">
                                    {{ $s->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-[#8896A6]">Belum ada staf terdaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-[#E2E7EF]">{{ $staff->links() }}</div>
    </div>

    <!-- Add / Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs px-4">
            <div class="bg-white border border-[#E2E7EF] rounded-2xl w-full max-w-md p-6 shadow-2xl space-y-5 relative overflow-hidden">
                <!-- Top Accent Line -->
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-[#D4A853] via-[#10B981] to-[#3B82F6]"></div>

                <div class="flex justify-between items-center border-b border-[#E2E7EF] pb-3">
                    <h3 class="text-lg font-bold text-[#1A1D23]">{{ $editingId ? 'Edit' : 'Tambah' }} Akun Staf</h3>
                    <button wire:click="closeModal" class="text-[#8896A6] hover:text-[#1A1D23] cursor-pointer p-1 rounded-lg hover:bg-slate-100 transition-all">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568]">Nama Lengkap</label>
                        <input wire:model="staffName" type="text" placeholder="Contoh: Rian Hidayat" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all">
                        @error('staffName') <p class="text-2xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568]">Alamat Email</label>
                        <input wire:model="staffEmail" type="email" placeholder="Contoh: rian@kliinlaundry.com" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all">
                        @error('staffEmail') <p class="text-2xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568]">Kata Sandi (Password) {{ $editingId ? '(kosongkan jika tidak diubah)' : '' }}</label>
                        <input wire:model="staffPassword" type="password" placeholder="Masukkan password staf..." class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] placeholder-[#8896A6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568]">Wewenang (Role)</label>
                        <select wire:model="roleId" class="w-full mt-1 px-3 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]/15 focus:border-[#1E3A5F] transition-all">
                            <option value="">Pilih Wewenang Staf</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                        @error('roleId') <p class="text-2xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
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
