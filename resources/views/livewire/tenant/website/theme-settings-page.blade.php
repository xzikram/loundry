<div class="space-y-8 max-w-4xl">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-[#1A1D23]">Pengaturan Tema</h1>
        <p class="text-sm text-[#8896A6] mt-1">Sesuaikan identitas brand dan skema warna landing page Anda</p>
    </div>

    @if($saved)
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl text-sm font-medium flex items-center space-x-2">
            <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>Tema berhasil disimpan! Perubahan akan langsung diterapkan pada landing page.</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Colors & Font Settings -->
        <div class="lg:col-span-7 space-y-6">
            <!-- Brand Colors -->
            <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 space-y-5">
                <div class="border-b border-[#E2E7EF] pb-3">
                    <h3 class="text-md font-bold text-[#1A1D23]">Warna Brand</h3>
                    <p class="text-xs text-[#8896A6]">Pilih warna primer, sekunder, dan aksen untuk teks atau tombol</p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568] uppercase tracking-wider">Warna Utama (Primary)</label>
                        <div class="flex mt-1.5 rounded-xl border border-[#E2E7EF] overflow-hidden">
                            <input wire:model="primaryColor" type="color" class="w-12 h-10 border-0 cursor-pointer">
                            <input wire:model="primaryColor" type="text" class="flex-1 px-3 py-2 text-sm text-[#1A1D23] bg-white border-l border-[#E2E7EF] focus:outline-none uppercase">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568] uppercase tracking-wider">Warna Sekunder (Secondary)</label>
                        <div class="flex mt-1.5 rounded-xl border border-[#E2E7EF] overflow-hidden">
                            <input wire:model="secondaryColor" type="color" class="w-12 h-10 border-0 cursor-pointer">
                            <input wire:model="secondaryColor" type="text" class="flex-1 px-3 py-2 text-sm text-[#1A1D23] bg-white border-l border-[#E2E7EF] focus:outline-none uppercase">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568] uppercase tracking-wider">Warna Aksen (Accent)</label>
                        <div class="flex mt-1.5 rounded-xl border border-[#E2E7EF] overflow-hidden">
                            <input wire:model="accentColor" type="color" class="w-12 h-10 border-0 cursor-pointer">
                            <input wire:model="accentColor" type="text" class="flex-1 px-3 py-2 text-sm text-[#1A1D23] bg-white border-l border-[#E2E7EF] focus:outline-none uppercase">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Layout Colors -->
            <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 space-y-5">
                <div class="border-b border-[#E2E7EF] pb-3">
                    <h3 class="text-md font-bold text-[#1A1D23]">Skema Latar Belakang & Teks</h3>
                    <p class="text-xs text-[#8896A6]">Atur kontras halaman Anda agar tetap mudah dibaca</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568] uppercase tracking-wider">Warna Latar (Background)</label>
                        <div class="flex mt-1.5 rounded-xl border border-[#E2E7EF] overflow-hidden">
                            <input wire:model="backgroundColor" type="color" class="w-12 h-10 border-0 cursor-pointer">
                            <input wire:model="backgroundColor" type="text" class="flex-1 px-3 py-2 text-sm text-[#1A1D23] bg-white border-l border-[#E2E7EF] focus:outline-none uppercase">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568] uppercase tracking-wider">Warna Card (Surface)</label>
                        <div class="flex mt-1.5 rounded-xl border border-[#E2E7EF] overflow-hidden">
                            <input wire:model="surfaceColor" type="color" class="w-12 h-10 border-0 cursor-pointer">
                            <input wire:model="surfaceColor" type="text" class="flex-1 px-3 py-2 text-sm text-[#1A1D23] bg-white border-l border-[#E2E7EF] focus:outline-none uppercase">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568] uppercase tracking-wider">Warna Teks Utama</label>
                        <div class="flex mt-1.5 rounded-xl border border-[#E2E7EF] overflow-hidden">
                            <input wire:model="textColor" type="color" class="w-12 h-10 border-0 cursor-pointer">
                            <input wire:model="textColor" type="text" class="flex-1 px-3 py-2 text-sm text-[#1A1D23] bg-white border-l border-[#E2E7EF] focus:outline-none uppercase">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568] uppercase tracking-wider">Warna Heading/Judul</label>
                        <div class="flex mt-1.5 rounded-xl border border-[#E2E7EF] overflow-hidden">
                            <input wire:model="headingColor" type="color" class="w-12 h-10 border-0 cursor-pointer">
                            <input wire:model="headingColor" type="text" class="flex-1 px-3 py-2 text-sm text-[#1A1D23] bg-white border-l border-[#E2E7EF] focus:outline-none uppercase">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Typography & Styles -->
            <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 space-y-5">
                <div class="border-b border-[#E2E7EF] pb-3">
                    <h3 class="text-md font-bold text-[#1A1D23]">Tipografi & Desain Tombol</h3>
                    <p class="text-xs text-[#8896A6]">Atur tipe huruf dan bentuk tombol agar selaras</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568] uppercase tracking-wider">Font Judul (Heading)</label>
                        <select wire:model="headingFont" class="w-full mt-1.5 px-4 py-2.5 border border-[#E2E7EF] bg-white text-[#1A1D23] rounded-xl text-sm focus:outline-none">
                            <option value="Outfit">Outfit (Rekomendasi)</option>
                            <option value="Inter">Inter</option>
                            <option value="Roboto">Roboto</option>
                            <option value="Poppins">Poppins</option>
                            <option value="Playfair Display">Playfair Display</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568] uppercase tracking-wider">Font Konten (Body)</label>
                        <select wire:model="bodyFont" class="w-full mt-1.5 px-4 py-2.5 border border-[#E2E7EF] bg-white text-[#1A1D23] rounded-xl text-sm focus:outline-none">
                            <option value="Outfit">Outfit</option>
                            <option value="Inter">Inter (Rekomendasi)</option>
                            <option value="Roboto">Roboto</option>
                            <option value="Open Sans">Open Sans</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568] uppercase tracking-wider">Model Tombol</label>
                        <select wire:model="buttonStyle" class="w-full mt-1.5 px-4 py-2.5 border border-[#E2E7EF] bg-white text-[#1A1D23] rounded-xl text-sm focus:outline-none">
                            <option value="rounded-none">Kotak Tajam (No Radius)</option>
                            <option value="rounded-md">Kotak Sudut Lembut (Medium)</option>
                            <option value="rounded-xl">Kotak Premium (Extra Large)</option>
                            <option value="rounded-full">Sudut Melengkung Penuh (Pill)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#4A5568] uppercase tracking-wider">Lengkungan Elemen (Radius)</label>
                        <select wire:model="borderRadius" class="w-full mt-1.5 px-4 py-2.5 border border-[#E2E7EF] bg-white text-[#1A1D23] rounded-xl text-sm focus:outline-none">
                            <option value="0px">0px</option>
                            <option value="6px">6px</option>
                            <option value="12px">12px (Premium)</option>
                            <option value="20px">20px</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Preview Panel -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 sticky top-20 space-y-5">
                <div class="border-b border-[#E2E7EF] pb-3">
                    <h3 class="text-md font-bold text-[#1A1D23]">Pratinjau Langsung (Live Preview)</h3>
                    <p class="text-xs text-[#8896A6]">Simulasi tampilan elemen dengan tema yang dipilih</p>
                </div>

                <!-- Simulation Box -->
                <div class="p-6 rounded-2xl border transition-all" style="background-color: {{ $backgroundColor }}; border-color: {{ $primaryColor }}20; border-radius: {{ $borderRadius }};">
                    <div class="p-4 bg-white border border-[#E2E7EF] shadow-sm space-y-4" style="background-color: {{ $surfaceColor }}; border-radius: {{ $borderRadius }};">
                        <span class="inline-block text-[9px] font-bold uppercase tracking-wider px-2 py-0.5" style="color: {{ $accentColor }}; background-color: {{ $accentColor }}15; border-radius: 9999px;">Tag Kategori</span>
                        <h4 class="text-md font-bold leading-tight" style="color: {{ $headingColor }}; font-family: '{{ $headingFont }}', sans-serif;">Judul Utama Halaman</h4>
                        <p class="text-xs leading-relaxed" style="color: {{ $textColor }}; font-family: '{{ $bodyFont }}', sans-serif;">Ini adalah simulasi paragraf untuk melihat kontras teks dengan latar belakang.</p>
                        
                        <div class="pt-2 flex space-x-2">
                            <button class="px-4 py-2 text-xs font-semibold text-white transition-all shadow-md" style="background-color: {{ $primaryColor }}; border-radius: {{ $borderRadius }};">
                                Tombol Utama
                            </button>
                            <button class="px-4 py-2 text-xs font-semibold border transition-all" style="color: {{ $textColor }}; border-color: {{ $primaryColor }}30; background-color: transparent; border-radius: {{ $borderRadius }};">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button wire:click="save" class="px-6 py-2.5 bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] hover:from-[#2A5082] hover:to-[#1E3A5F] text-white rounded-xl text-xs font-bold shadow-lg shadow-[#1E3A5F]/10 transition-all cursor-pointer">
                        Simpan Tema
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
