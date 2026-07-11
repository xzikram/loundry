<div class="space-y-8 max-w-3xl">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-[#1A1D23]">Profil Saya</h1>
        <p class="text-sm text-[#8896A6] mt-1">Ubah data diri dan password Anda</p>
    </div>

    @if($saved)
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl text-sm font-medium flex items-center space-x-2">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>Profil berhasil diperbarui!</span>
        </div>
    @endif

    <!-- Profile Info -->
    <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 space-y-5">
        <div class="border-b border-[#E2E7EF] pb-3">
            <h3 class="text-lg font-bold text-[#1A1D23]">Informasi Pribadi</h3>
            <p class="text-xs text-[#8896A6]">Nama lengkap, email, dan nomor WhatsApp Anda</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-[#4A5568]">Nama Lengkap</label>
                <input wire:model="name" type="text" class="w-full mt-1 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:border-[#1E3A5F]">
                @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-[#4A5568]">No. WhatsApp / Telepon</label>
                <input wire:model="phone" type="text" class="w-full mt-1 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:border-[#1E3A5F]">
                @error('phone') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-[#4A5568]">Email</label>
                <input wire:model="email" type="email" class="w-full mt-1 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:border-[#1E3A5F]">
                @error('email') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <!-- Password Update -->
    <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 space-y-5">
        <div class="border-b border-[#E2E7EF] pb-3">
            <h3 class="text-lg font-bold text-[#1A1D23]">Ganti Password</h3>
            <p class="text-xs text-[#8896A6]">Kosongkan jika Anda tidak ingin mengganti password</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-[#4A5568]">Password Baru</label>
                <input wire:model="password" type="password" class="w-full mt-1 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:border-[#1E3A5F]">
                @error('password') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-[#4A5568]">Konfirmasi Password Baru</label>
                <input wire:model="password_confirmation" type="password" class="w-full mt-1 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:border-[#1E3A5F]">
            </div>
        </div>
    </div>

    <div class="flex justify-end">
        <button wire:click="save" class="px-6 py-2.5 bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] text-white rounded-xl text-sm font-bold shadow-lg shadow-[#1E3A5F]/10 cursor-pointer hover:shadow-xl transition-all">
            Simpan Perubahan
        </button>
    </div>
</div>
