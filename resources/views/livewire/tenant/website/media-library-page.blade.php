<div class="space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-[#1A1D23]">Pustaka Media</h1>
            <p class="text-sm text-[#8896A6] mt-1">Unggah dan kelola gambar atau video untuk landing page Anda</p>
        </div>
        
        <!-- File Uploader Button -->
        <label class="px-5 py-2.5 bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] hover:from-[#2A5082] hover:to-[#1E3A5F] text-white rounded-xl text-xs font-bold shadow-lg shadow-[#1E3A5F]/10 transition-all cursor-pointer flex items-center space-x-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            <span>Unggah Berkas</span>
            <input wire:model="uploads" type="file" multiple class="hidden" accept="image/*,video/*">
        </label>
    </div>

    <!-- Filter & Search Controls -->
    <div class="bg-white border border-[#E2E7EF] p-4 rounded-2xl flex flex-col md:flex-row gap-4 items-center justify-between shadow-sm">
        <div class="flex items-center space-x-4 w-full md:w-auto">
            <div class="relative w-full md:w-64">
                <input wire:model.live="search" wire:keyup="searchMedia" type="text" placeholder="Cari nama berkas..." class="w-full pl-9 pr-4 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]">
                <svg class="absolute left-3 top-2.5 h-4 w-4 text-[#8896A6]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            
            <select wire:model.live="filterType" wire:change="loadMedia" class="px-4 py-2 border border-[#E2E7EF] bg-white text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]">
                <option value="all">Semua Tipe</option>
                <option value="image">Gambar</option>
                <option value="video">Video</option>
            </select>
        </div>

        <div wire:loading wire:target="uploads" class="text-xs text-[#1E3A5F] font-semibold flex items-center space-x-2">
            <svg class="animate-spin h-4 w-4 text-[#D4A853]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89M21 3v5h-5"/></svg>
            <span>Mengunggah berkas...</span>
        </div>
    </div>

    <!-- Media Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
        @forelse($mediaFiles as $file)
            <div wire:click="showDetails({{ $file->id }})" class="bg-white border border-[#E2E7EF] rounded-2xl overflow-hidden shadow-sm hover:shadow-md cursor-pointer transition-all group relative">
                
                <!-- Image/Video Thumbnail -->
                <div class="aspect-square bg-[#F8F9FC] flex items-center justify-center relative overflow-hidden border-b border-[#E2E7EF]">
                    @if($file->file_type === 'image')
                        <img src="{{ $file->file_url }}" alt="{{ $file->alt_text }}" class="object-cover w-full h-full group-hover:scale-105 transition-all">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center bg-slate-900/10 group-hover:bg-slate-900/30 transition-all z-10">
                            <div class="h-10 w-10 rounded-full bg-white/80 flex items-center justify-center text-[#1E3A5F]">
                                ▶
                            </div>
                        </div>
                        <video src="{{ $file->file_url }}" class="object-cover w-full h-full" muted></video>
                    @endif
                </div>

                <!-- Info Block -->
                <div class="p-3">
                    <p class="text-xs font-bold text-[#1A1D23] truncate">{{ $file->original_name }}</p>
                    <p class="text-[10px] text-[#8896A6] mt-0.5">{{ number_format($file->file_size / 1024, 0) }} KB</p>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white border border-[#E2E7EF] rounded-2xl">
                <svg class="mx-auto h-12 w-12 text-[#8896A6]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <h3 class="mt-2 text-sm font-bold text-[#1A1D23]">Pustaka media kosong</h3>
                <p class="mt-1 text-xs text-[#8896A6]">Belum ada berkas media. Mulai dengan mengunggah gambar atau video.</p>
            </div>
        @endforelse
    </div>

    <!-- Details/Edit Modal (Tailwind CSS Drawer/Modal style) -->
    @if($isDetailModalOpen && $selectedMediaId)
        @php $media = \App\Models\Tenant\LandingMedia::find($selectedMediaId); @endphp
        @if($media)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
                <div class="bg-white border border-[#E2E7EF] rounded-2xl max-w-2xl w-full overflow-hidden shadow-2xl flex flex-col md:flex-row">
                    
                    <!-- Preview side -->
                    <div class="md:w-1/2 bg-[#F8F9FC] border-r border-[#E2E7EF] p-6 flex flex-col items-center justify-center min-h-[300px]">
                        @if($media->file_type === 'image')
                            <img src="{{ $media->file_url }}" alt="{{ $media->alt_text }}" class="max-w-full max-h-[250px] object-contain rounded-lg shadow">
                        @else
                            <video src="{{ $media->file_url }}" controls class="max-w-full max-h-[250px] rounded-lg shadow"></video>
                        @endif
                        
                        <div class="mt-4 text-center text-[10px] text-[#8896A6] space-y-0.5">
                            <p class="font-mono truncate max-w-[200px]">{{ $media->original_name }}</p>
                            <p>{{ $media->mime_type }} &middot; {{ number_format($media->file_size / 1024, 0) }} KB</p>
                            @if($media->width) <p>{{ $media->width }}x{{ $media->height }} px</p> @endif
                        </div>
                    </div>

                    <!-- Details side -->
                    <div class="md:w-1/2 p-6 flex flex-col justify-between">
                        <div class="space-y-4">
                            <div class="border-b border-[#E2E7EF] pb-3 flex justify-between items-center">
                                <h3 class="text-sm font-bold text-[#1A1D23]">Detail Media</h3>
                                <button wire:click="$set('isDetailModalOpen', false)" class="text-[#8896A6] hover:text-[#1A1D23]">✕</button>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#4A5568] uppercase tracking-wider">Judul Media</label>
                                <input wire:model="title" type="text" class="w-full mt-1.5 px-4 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#4A5568] uppercase tracking-wider">Deskripsi Alt (Alt Text)</label>
                                <input wire:model="altText" type="text" placeholder="Deskripsi untuk SEO (opsional)" class="w-full mt-1.5 px-4 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]">
                                <span class="text-[9px] text-[#8896A6] mt-1 block">Membantu optimasi SEO pencarian gambar Google.</span>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#4A5568] uppercase tracking-wider">Tautan File (URL)</label>
                                <div class="flex mt-1.5">
                                    <input type="text" readonly value="{{ $media->file_url }}" id="media-url" class="flex-1 px-3 py-1.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#8896A6] font-mono text-[10px] rounded-l-xl focus:outline-none">
                                    <button onclick="navigator.clipboard.writeText('{{ $media->file_url }}'); alert('Tautan disalin!');" type="button" class="px-3 bg-[#E2E7EF] text-[#4A5568] text-xs font-bold rounded-r-xl hover:bg-[#CBD5E1]">
                                        Salin
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-4 border-t border-[#E2E7EF] mt-6">
                            <button onclick="confirm('Apakah Anda yakin ingin menghapus media ini secara permanen?') || event.stopImmediatePropagation()" wire:click="delete({{ $media->id }})" type="button" class="text-xs font-bold text-rose-500 hover:text-rose-700">
                                Hapus Berkas
                            </button>
                            <button wire:click="saveDetails" type="button" class="px-4 py-2 bg-[#1E3A5F] hover:bg-[#2A5082] text-white rounded-xl text-xs font-bold shadow-md">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        @endif
    @endif
</div>
