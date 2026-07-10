<div class="space-y-8 max-w-6xl">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-[#1A1D23]">Halaman Website</h1>
            <p class="text-sm text-[#8896A6] mt-1">Buat, kelola, dan terbitkan halaman custom landing page</p>
        </div>
        <button wire:click="openCreateForm" class="px-5 py-2.5 bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] hover:from-[#2A5082] hover:to-[#1E3A5F] text-white rounded-xl text-xs font-bold shadow-lg shadow-[#1E3A5F]/10 transition-all cursor-pointer">
            + Buat Halaman Baru
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Pages Table -->
        <div class="lg:col-span-8 space-y-4">
            <div class="bg-white border border-[#E2E7EF] rounded-2xl overflow-hidden shadow-sm">
                <table class="min-w-full divide-y divide-[#E2E7EF] text-left">
                    <thead class="bg-[#F8F9FC] text-xs font-bold text-[#4A5568] uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Nama Halaman</th>
                            <th class="px-6 py-4">Slug</th>
                            <th class="px-6 py-4">Tipe</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E7EF] text-sm text-[#1A1D23]">
                        @forelse($pages as $page)
                            <tr>
                                <td class="px-6 py-4 font-bold">
                                    <div class="flex items-center space-x-2">
                                        <span>{{ $page->name }}</span>
                                        @if($page->is_homepage)
                                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-200 text-[10px] rounded-full font-bold">Beranda</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-[#8896A6]">
                                    /{{ $page->slug }}
                                </td>
                                <td class="px-6 py-4 text-xs capitalize text-[#4A5568]">
                                    {{ str_replace('_', ' ', $page->page_type) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 text-xs rounded-full font-bold {{ $page->status === 'published' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-slate-50 text-slate-500 border border-slate-200' }}">
                                        {{ ucfirst($page->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-3 text-xs font-bold">
                                    <a href="{{ route('tenant.website.builder', ['id' => $page->id]) }}" class="text-[#1E3A5F] hover:text-[#2A5082]">
                                        Edit Builder
                                    </a>
                                    
                                    @if(!$page->is_homepage)
                                        <button wire:click="setAsHomepage({{ $page->id }})" class="text-amber-600 hover:text-amber-700">
                                            Jadikan Beranda
                                        </button>
                                        <button onclick="confirm('Hapus halaman ini beserta seluruh konten section di dalamnya?') || event.stopImmediatePropagation()" wire:click="delete({{ $page->id }})" class="text-rose-500 hover:text-rose-700">
                                            Hapus
                                        </button>
                                    @endif
                                    
                                    <button wire:click="editProperties({{ $page->id }})" class="text-slate-500 hover:text-slate-700">
                                        Properti
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-16 text-[#8896A6]">
                                    <svg class="mx-auto h-12 w-12 text-[#8896A6]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <h3 class="mt-2 text-sm font-bold text-[#1A1D23]">Belum ada halaman</h3>
                                    <p class="mt-1 text-xs text-[#8896A6]">Mulai dengan membuat halaman landing page baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Form Sidebar Panel -->
        <div class="lg:col-span-4 space-y-6">
            @if($isFormOpen)
                <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 space-y-5 shadow-md">
                    <div class="border-b border-[#E2E7EF] pb-3 flex justify-between items-center">
                        <h3 class="text-md font-bold text-[#1A1D23]">{{ $editingPageId ? 'Edit Properti Halaman' : 'Buat Halaman Baru' }}</h3>
                        <button wire:click="$set('isFormOpen', false)" class="text-[#8896A6] hover:text-[#1A1D23]">✕</button>
                    </div>

                    <form wire:submit.prevent="save" class="space-y-4 text-xs font-semibold text-[#4A5568]">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider">Nama Halaman</label>
                            <input wire:model.live="name" type="text" placeholder="Contoh: Promo Ramadhan" class="w-full mt-1.5 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]">
                            @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider">Slug URL</label>
                            <div class="flex mt-1.5 rounded-xl border border-[#E2E7EF] overflow-hidden">
                                <span class="px-3 py-2.5 bg-slate-100 border-r border-[#E2E7EF] text-[#8896A6] text-xs">/</span>
                                <input wire:model="slug" type="text" placeholder="promo-ramadhan" class="flex-1 px-3 py-2.5 bg-[#F8F9FC] text-[#1A1D23] focus:outline-none">
                            </div>
                            @error('slug') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider">Tipe Halaman</label>
                            <select wire:model="pageType" class="w-full mt-1.5 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none">
                                <option value="regular_page">Halaman Biasa (Regular)</option>
                                <option value="campaign_page">Halaman Kampanye Iklan</option>
                                @if(!$editingPageId)
                                    <option value="homepage">Halaman Beranda Utama</option>
                                @endif
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider">Status Publikasi</label>
                            <select wire:model="status" class="w-full mt-1.5 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none">
                                <option value="draft">Draft (Disembunyikan)</option>
                                <option value="published">Published (Diterbitkan)</option>
                            </select>
                        </div>

                        <div class="flex justify-end space-x-2 pt-2 border-t border-[#E2E7EF]">
                            <button type="button" wire:click="$set('isFormOpen', false)" class="px-4 py-2 text-xs font-semibold border border-[#E2E7EF] text-[#4A5568] rounded-xl hover:bg-[#F8F9FC]">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 bg-[#1E3A5F] hover:bg-[#2A5082] text-white rounded-xl text-xs font-bold shadow-md">
                                Simpan Halaman
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-[#F8F9FC] border border-dashed border-[#E2E7EF] rounded-2xl p-8 text-center text-[#8896A6]">
                    <p class="text-xs">Klik tombol **Buat Halaman Baru** untuk membuat rute URL landing page kustom promosi laundry Anda secara mandiri.</p>
                </div>
            @endif
        </div>
    </div>
</div>
