<div class="space-y-8 max-w-3xl">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-[#1A1D23]">Pengaturan</h1>
        <p class="text-sm text-[#8896A6] mt-1">Konfigurasi outlet dan preferensi</p>
    </div>

    @if($saved)
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl text-sm font-medium flex items-center space-x-2">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>Pengaturan berhasil disimpan!</span>
        </div>
    @endif

    <!-- Outlet Info -->
    <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 space-y-5">
        <div class="border-b border-[#E2E7EF] pb-3">
            <h3 class="text-lg font-bold text-[#1A1D23]">Informasi Outlet</h3>
            <p class="text-xs text-[#8896A6]">Detail toko yang tampil di struk dan notifikasi</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-[#4A5568]">Nama Laundry</label>
                <input wire:model="laundryName" type="text" class="w-full mt-1 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:border-[#1E3A5F]">
                @error('laundryName') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-[#4A5568]">No. Telepon</label>
                <input wire:model="laundryPhone" type="text" class="w-full mt-1 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:border-[#1E3A5F]">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#4A5568]">Email</label>
                <input wire:model="laundryEmail" type="email" class="w-full mt-1 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:border-[#1E3A5F]">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#4A5568]">Alamat</label>
                <input wire:model="laundryAddress" type="text" class="w-full mt-1 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:border-[#1E3A5F]">
            </div>
        </div>
    </div>

    <!-- Tax & Currency -->
    <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 space-y-5">
        <div class="border-b border-[#E2E7EF] pb-3">
            <h3 class="text-lg font-bold text-[#1A1D23]">Pajak & Mata Uang</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-[#4A5568]">Tarif Pajak (%)</label>
                <input wire:model="taxRate" type="number" step="0.1" class="w-full mt-1 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:border-[#1E3A5F]">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#4A5568]">Mata Uang</label>
                <select wire:model="currency" class="w-full mt-1 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm">
                    <option value="IDR">IDR - Rupiah</option>
                    <option value="USD">USD - Dollar</option>
                    <option value="MYR">MYR - Ringgit</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Pakasir Payment Gateway -->
    <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 space-y-5">
        <div class="border-b border-[#E2E7EF] pb-3">
            <h3 class="text-lg font-bold text-[#1A1D23]">Integrasi Pembayaran Pakasir</h3>
            <p class="text-xs text-[#8896A6]">Konfigurasi Project Slug dan API Key untuk menerima pembayaran digital via QRIS/VA otomatis.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-[#4A5568]">Pakasir Project Slug</label>
                <input wire:model="pakasirProjectSlug" type="text" placeholder="Contoh: laundry-super" class="w-full mt-1 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:border-[#1E3A5F]">
                @error('pakasirProjectSlug') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-[#4A5568]">Pakasir API Key</label>
                <input wire:model="pakasirApiKey" type="password" placeholder="pk_live_..." class="w-full mt-1 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:border-[#1E3A5F]">
                @error('pakasirApiKey') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <!-- Outlet Info (Read Only) -->
    @if($outlet)
    <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 space-y-3">
        <div class="border-b border-[#E2E7EF] pb-3">
            <h3 class="text-lg font-bold text-[#1A1D23]">Info Outlet Aktif</h3>
        </div>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><span class="text-[#8896A6]">Nama Outlet:</span> <span class="font-semibold text-[#1A1D23]">{{ $outlet->name }}</span></div>
            <div><span class="text-[#8896A6]">Alamat:</span> <span class="font-semibold text-[#1A1D23]">{{ $outlet->address ?? '-' }}</span></div>
            <div><span class="text-[#8896A6]">Telepon:</span> <span class="font-semibold text-[#1A1D23]">{{ $outlet->phone ?? '-' }}</span></div>
            <div><span class="text-[#8896A6]">Status:</span> <span class="font-semibold text-emerald-600">{{ $outlet->is_active ? 'Aktif' : 'Nonaktif' }}</span></div>
        </div>
    </div>
    @endif

    <div class="flex justify-end">
        <button wire:click="save" class="px-6 py-2.5 bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] text-white rounded-xl text-sm font-bold shadow-lg shadow-[#1E3A5F]/10 cursor-pointer hover:shadow-xl transition-all">
            Simpan Pengaturan
        </button>
    </div>
</div>
