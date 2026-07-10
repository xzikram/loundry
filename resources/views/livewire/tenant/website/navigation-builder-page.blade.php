<div class="space-y-8 max-w-4xl">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-[#1A1D23]">Menu Navigasi</h1>
            <p class="text-sm text-[#8896A6] mt-1">Kelola tautan menu header dan footer pada landing page Anda</p>
        </div>
        <button wire:click="openCreateForm" class="px-5 py-2.5 bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] hover:from-[#2A5082] hover:to-[#1E3A5F] text-white rounded-xl text-xs font-bold shadow-lg shadow-[#1E3A5F]/10 transition-all cursor-pointer">
            + Tambah Menu Baru
        </button>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-[#E2E7EF] flex space-x-6 text-sm">
        <button wire:click="switchTab('header')" class="pb-3 font-semibold transition-all border-b-2 {{ $menuType === 'header' ? 'border-[#1E3A5F] text-[#1E3A5F]' : 'border-transparent text-[#8896A6] hover:text-[#1A1D23]' }}">
            Header Menu (Navigasi Atas)
        </button>
        <button wire:click="switchTab('footer')" class="pb-3 font-semibold transition-all border-b-2 {{ $menuType === 'footer' ? 'border-[#1E3A5F] text-[#1E3A5F]' : 'border-transparent text-[#8896A6] hover:text-[#1A1D23]' }}">
            Footer Menu (Navigasi Bawah)
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Menu Tree List -->
        <div class="lg:col-span-7 space-y-4">
            <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6">
                <div class="space-y-3">
                    @forelse($menus as $menu)
                        <!-- Parent Item -->
                        <div class="bg-[#F8F9FC] border border-[#E2E7EF] rounded-xl p-4 flex items-center justify-between shadow-sm">
                            <div class="flex items-center space-x-3">
                                <div class="cursor-pointer text-[#8896A6] hover:text-[#1A1D23]">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                </div>
                                <div>
                                    <span class="text-sm font-bold text-[#1A1D23]">{{ $menu->label }}</span>
                                    <span class="text-xs text-[#8896A6] block font-mono mt-0.5">{{ $menu->url }}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <button wire:click="moveUp({{ $menu->id }})" class="p-1.5 hover:bg-white text-[#8896A6] hover:text-[#1E3A5F] rounded border border-transparent hover:border-[#E2E7EF] transition-all">
                                    ▲
                                </button>
                                <button wire:click="moveDown({{ $menu->id }})" class="p-1.5 hover:bg-white text-[#8896A6] hover:text-[#1E3A5F] rounded border border-transparent hover:border-[#E2E7EF] transition-all">
                                    ▼
                                </button>
                                <button wire:click="edit({{ $menu->id }})" class="p-1.5 hover:bg-white text-[#8896A6] hover:text-[#1E3A5F] rounded border border-transparent hover:border-[#E2E7EF] transition-all text-xs">
                                    Edit
                                </button>
                                <button onclick="confirm('Apakah Anda yakin ingin menghapus menu ini beserta submenunya?') || event.stopImmediatePropagation()" wire:click="delete({{ $menu->id }})" class="p-1.5 hover:bg-rose-50 text-rose-400 hover:text-rose-600 rounded border border-transparent hover:border-rose-100 transition-all text-xs">
                                    Hapus
                                </button>
                            </div>
                        </div>

                        <!-- Children Items -->
                        @foreach($menu->children as $child)
                            <div class="ml-8 bg-white border border-[#E2E7EF] rounded-xl p-3 flex items-center justify-between shadow-sm">
                                <div class="flex items-center space-x-3">
                                    <span class="text-[#8896A6]">└─</span>
                                    <div>
                                        <span class="text-xs font-bold text-[#4A5568]">{{ $child->label }}</span>
                                        <span class="text-[10px] text-[#8896A6] block font-mono mt-0.5">{{ $child->url }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <button wire:click="moveUp({{ $child->id }})" class="p-1 hover:bg-[#F8F9FC] text-[#8896A6] hover:text-[#1E3A5F] rounded transition-all text-[10px]">
                                        ▲
                                    </button>
                                    <button wire:click="moveDown({{ $child->id }})" class="p-1 hover:bg-[#F8F9FC] text-[#8896A6] hover:text-[#1E3A5F] rounded transition-all text-[10px]">
                                        ▼
                                    </button>
                                    <button wire:click="edit({{ $child->id }})" class="p-1 hover:bg-[#F8F9FC] text-[#8896A6] hover:text-[#1E3A5F] rounded transition-all text-xs">
                                        Edit
                                    </button>
                                    <button onclick="confirm('Hapus submenu ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $child->id }})" class="p-1 hover:bg-rose-50 text-rose-400 hover:text-rose-600 rounded transition-all text-xs">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @empty
                        <div class="text-center py-12 bg-[#F8F9FC] border border-dashed border-[#E2E7EF] rounded-xl">
                            <svg class="mx-auto h-12 w-12 text-[#8896A6]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            <h3 class="mt-2 text-sm font-bold text-[#1A1D23]">Belum ada rute menu</h3>
                            <p class="mt-1 text-xs text-[#8896A6]">Mulai dengan menambahkan menu baru untuk navigasi.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Form Builder Panel -->
        <div class="lg:col-span-5 space-y-6">
            @if($isFormOpen)
                <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 space-y-5 sticky top-20 shadow-md">
                    <div class="border-b border-[#E2E7EF] pb-3">
                        <h3 class="text-md font-bold text-[#1A1D23]">{{ $editingMenuId ? 'Edit Menu' : 'Tambah Menu Baru' }}</h3>
                        <p class="text-xs text-[#8896A6]">Isi label dan tautan link navigasi tujuan</p>
                    </div>

                    <form wire:submit.prevent="save" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#4A5568] uppercase tracking-wider">Label Menu</label>
                            <input wire:model="label" type="text" placeholder="Contoh: Tentang Kami, Harga" class="w-full mt-1.5 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:border-[#1E3A5F]">
                            @error('label') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#4A5568] uppercase tracking-wider">Tautan Link (URL)</label>
                            <input wire:model="url" type="text" placeholder="Contoh: #about, /register, https://..." class="w-full mt-1.5 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none focus:border-[#1E3A5F]">
                            @error('url') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#4A5568] uppercase tracking-wider">Target Tautan</label>
                            <select wire:model="target" class="w-full mt-1.5 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none">
                                <option value="_self">Tab Saat Ini (_self)</option>
                                <option value="_blank">Tab Baru (_blank)</option>
                            </select>
                        </div>

                        @if($menuType === 'header')
                            <div>
                                <label class="block text-xs font-semibold text-[#4A5568] uppercase tracking-wider">Menu Induk (Submenu Dari)</label>
                                <select wire:model="parentId" class="w-full mt-1.5 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none">
                                    <option value="">-- Menu Utama --</option>
                                    @foreach($parentMenus as $pm)
                                        <option value="{{ $pm->id }}">{{ $pm->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div>
                            <label class="block text-xs font-semibold text-[#4A5568] uppercase tracking-wider">Urutan (Sort Order)</label>
                            <input wire:model="sortOrder" type="number" class="w-full mt-1.5 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-sm focus:outline-none">
                        </div>

                        <div class="flex justify-end space-x-2 pt-2">
                            <button type="button" wire:click="$set('isFormOpen', false)" class="px-4 py-2 text-xs font-semibold border border-[#E2E7EF] text-[#4A5568] rounded-xl hover:bg-[#F8F9FC]">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 bg-[#1E3A5F] hover:bg-[#2A5082] text-white rounded-xl text-xs font-bold shadow-lg shadow-[#1E3A5F]/10 transition-all cursor-pointer">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-[#F8F9FC] border border-dashed border-[#E2E7EF] rounded-2xl p-8 text-center text-[#8896A6]">
                    <p class="text-xs">Klik tombol **Tambah Menu Baru** untuk menyusun rute header atau footer landing page Anda secara instan.</p>
                </div>
            @endif
        </div>
    </div>
</div>
