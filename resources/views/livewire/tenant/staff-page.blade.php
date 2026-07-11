<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-[#1A1D23]">Staf</h1>
            <p class="text-sm text-[#8896A6] mt-1">Kelola pengguna dan hak akses</p>
        </div>
        <button wire:click="openCreate" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] shadow-lg shadow-[#1E3A5F]/10 cursor-pointer">
            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Staf
        </button>
    </div>

    <div class="bg-white border border-[#E2E7EF] rounded-2xl overflow-hidden">
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
                    <tr class="hover:bg-[#F8F9FC]">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-[#1E3A5F] to-[#2A5082] text-white flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr($s->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-[#1A1D23]">{{ $s->name }}</p>
                                    <p class="text-xs text-[#8896A6]">{{ $s->phone ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">{{ $s->email }}</td>
                        <td class="px-6 py-4"><span class="text-xs px-2.5 py-0.5 rounded-full bg-[#1E3A5F]/5 text-[#1E3A5F] border border-[#1E3A5F]/10 font-semibold capitalize">{{ $s->role->name ?? '-' }}</span></td>
                        <td class="px-6 py-4">
                            @if($s->is_active)
                                <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 font-semibold">Aktif</span>
                            @else
                                <span class="text-xs px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-500 border border-rose-200 font-semibold">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button wire:click="openEdit({{ $s->id }})" class="px-3 py-1 text-xs bg-[#F8F9FC] hover:bg-[#E2E7EF] text-[#4A5568] font-semibold rounded-lg border border-[#E2E7EF] cursor-pointer">Edit</button>
                            <button wire:click="toggleActive({{ $s->id }})" class="px-3 py-1 text-xs {{ $s->is_active ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-emerald-50 text-emerald-600 border-emerald-200' }} font-semibold rounded-lg border cursor-pointer">
                                {{ $s->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-[#8896A6]">Belum ada staf.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-[#E2E7EF]">{{ $staff->links() }}</div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 backdrop-blur-sm">
            <div class="bg-white border border-[#E2E7EF] rounded-2xl w-full max-w-md p-6 shadow-2xl space-y-5">
                <div class="flex justify-between items-center border-b border-[#E2E7EF] pb-3">
                    <h3 class="text-lg font-bold text-[#1A1D23]">{{ $editingId ? 'Edit' : 'Tambah' }} Staf</h3>
                    <button wire:click="closeModal" class="text-[#8896A6] hover:text-[#1A1D23]"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#4A5568]">Nama</label>
                        <input wire:model="staffName" type="text" class="w-full mt-1 px-4 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm">
                        @error('staffName') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#4A5568]">Email</label>
                        <input wire:model="staffEmail" type="email" class="w-full mt-1 px-4 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm">
                        @error('staffEmail') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#4A5568]">Password {{ $editingId ? '(kosongkan jika tidak diubah)' : '' }}</label>
                        <input wire:model="staffPassword" type="password" class="w-full mt-1 px-4 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#4A5568]">Role</label>
                        <select wire:model="roleId" class="w-full mt-1 px-4 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm">
                            <option value="">Pilih Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                        @error('roleId') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
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
