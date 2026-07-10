<div class="h-screen flex flex-col bg-[#F8F9FC] text-[#1A1D23] overflow-hidden" style="font-family: 'Outfit', sans-serif;">
    
    <!-- Top Action Bar -->
    <header class="bg-white border-b border-[#E2E7EF] h-16 flex items-center justify-between px-6 shrink-0 z-25 shadow-sm">
        <div class="flex items-center space-x-4">
            <a href="{{ route('tenant.website.pages') }}" class="text-[#8896A6] hover:text-[#1A1D23] font-bold text-sm flex items-center space-x-1">
                <span>←</span> <span>Kembali</span>
            </a>
            <div class="h-5 w-px bg-[#E2E7EF]"></div>
            <div>
                <span class="text-sm font-bold text-[#1A1D23]">{{ $page->name }}</span>
                <span class="px-2 py-0.5 bg-slate-100 text-[#4A5568] border border-slate-200 text-[10px] rounded-full font-bold ml-2 uppercase">
                    {{ $page->status }}
                </span>
            </div>
        </div>

        <!-- Viewport Toggle Buttons -->
        <div class="hidden md:flex items-center bg-[#F8F9FC] p-1 border border-[#E2E7EF] rounded-xl space-x-1">
            <button wire:click="$set('viewportMode', 'desktop')" class="p-2 rounded-lg text-xs font-bold transition-all {{ $viewportMode === 'desktop' ? 'bg-white text-[#1E3A5F] shadow-sm' : 'text-[#8896A6] hover:text-[#1A1D23]' }}">
                💻 Desktop
            </button>
            <button wire:click="$set('viewportMode', 'tablet')" class="p-2 rounded-lg text-xs font-bold transition-all {{ $viewportMode === 'tablet' ? 'bg-white text-[#1E3A5F] shadow-sm' : 'text-[#8896A6] hover:text-[#1A1D23]' }}">
                📱 Tablet
            </button>
            <button wire:click="$set('viewportMode', 'mobile')" class="p-2 rounded-lg text-xs font-bold transition-all {{ $viewportMode === 'mobile' ? 'bg-white text-[#1E3A5F] shadow-sm' : 'text-[#8896A6] hover:text-[#1A1D23]' }}">
                📞 Mobile
            </button>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center space-x-3 text-xs font-bold">
            <span class="text-xs text-[#8896A6] font-medium mr-2">
                Status: <span class="text-emerald-500 font-bold">{{ $saveStatus }}</span>
            </span>
            
            @if($page->status === 'published')
                <button wire:click="saveDraft" class="px-4 py-2 border border-[#E2E7EF] hover:bg-[#F8F9FC] text-[#4A5568] rounded-xl transition-all cursor-pointer">
                    Kembalikan ke Draft
                </button>
            @else
                <button wire:click="publishPage" class="px-5 py-2 bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] hover:from-[#2A5082] hover:to-[#1E3A5F] text-white rounded-xl shadow-md transition-all cursor-pointer">
                    Terbitkan Website
                </button>
            @endif
        </div>
    </header>

    <!-- Workspace Area -->
    <div class="flex-1 flex overflow-hidden">
        
        <!-- Left Panel: Page Structure & Components -->
        <aside class="w-80 bg-white border-r border-[#E2E7EF] flex flex-col shrink-0 overflow-y-auto">
            <div class="p-4 border-b border-[#E2E7EF] flex justify-between items-center bg-[#F8F9FC]">
                <span class="text-xs font-bold text-[#4A5568] uppercase tracking-wider">Susunan Halaman</span>
                <button wire:click="openAddSectionModal" class="px-3 py-1.5 bg-[#1E3A5F] hover:bg-[#2A5082] text-white rounded-lg text-[10px] font-bold transition-all cursor-pointer">
                    + Tambah Bagian
                </button>
            </div>

            <div class="p-4 space-y-3 flex-1">
                @forelse($sections as $sec)
                    <div wire:click="selectSection({{ $sec->id }})" class="border rounded-xl p-3 cursor-pointer transition-all flex flex-col space-y-3 {{ $selectedSectionId === $sec->id ? 'border-[#1E3A5F] bg-[#1E3A5F]/5 shadow-sm' : 'border-[#E2E7EF] bg-white hover:bg-slate-50' }}">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-bold text-[#1A1D23] uppercase tracking-wider">{{ $sec->section_type }}</span>
                                @if(!$sec->is_visible)
                                    <span class="text-[9px] px-1.5 bg-rose-50 border border-rose-100 text-rose-500 rounded font-bold uppercase">Hidden</span>
                                @endif
                            </div>
                            
                            <!-- Visibility/Sort controllers -->
                            <div class="flex items-center space-x-1" onclick="event.stopPropagation();">
                                <button wire:click="moveUp({{ $sec->id }})" class="p-1 hover:bg-slate-100 text-slate-400 hover:text-slate-700 rounded text-[9px]">▲</button>
                                <button wire:click="moveDown({{ $sec->id }})" class="p-1 hover:bg-slate-100 text-slate-400 hover:text-slate-700 rounded text-[9px]">▼</button>
                                <button wire:click="toggleVisibility({{ $sec->id }})" class="p-1 hover:bg-slate-100 text-slate-400 hover:text-slate-700 rounded text-xs">👁️</button>
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-[10px] text-[#8896A6] font-medium border-t border-dashed border-[#E2E7EF] pt-2">
                            <span>Template: {{ $sec->template_key }}</span>
                            <div class="flex space-x-2" onclick="event.stopPropagation();">
                                <button wire:click="duplicateSection({{ $sec->id }})" class="hover:text-[#1E3A5F]">Duplikat</button>
                                <button onclick="confirm('Hapus section ini?') || event.stopImmediatePropagation()" wire:click="deleteSection({{ $sec->id }})" class="hover:text-rose-500">Hapus</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-400">
                        <p class="text-xs">Belum ada section di halaman ini.</p>
                    </div>
                @endforelse
            </div>
        </aside>

        <!-- Center Panel: Live Preview Iframe Container -->
        <main class="flex-1 flex items-center justify-center p-6 overflow-hidden relative bg-[#EFF2F5]">
            <div class="h-full w-full max-w-full flex items-center justify-center relative">
                <!-- Preview Canvas viewport simulator wrapper -->
                <div class="h-full bg-white border border-[#E2E7EF] rounded-2xl shadow-xl overflow-y-auto transition-all duration-300 relative select-none flex flex-col"
                     style="width: {{ $viewportMode === 'mobile' ? '375px' : ($viewportMode === 'tablet' ? '768px' : '100%') }};">
                    
                    <!-- Simulating the Landing Page styles dynamically -->
                    <div class="min-h-full flex flex-col flex-1" style="
                        --color-primary: {{ $theme->primary_color }};
                        --color-secondary: {{ $theme->secondary_color }};
                        --color-accent: {{ $theme->accent_color }};
                        --color-background: {{ $theme->background_color }};
                        --color-surface: {{ $theme->surface_color }};
                        --color-text: {{ $theme->text_color }};
                        --color-heading: {{ $theme->heading_color }};
                        --border-radius: {{ $theme->border_radius }};
                        background-color: var(--color-background);
                        color: var(--color-text);
                    ">
                        
                        <!-- Header Preview -->
                        <div class="bg-white border-b p-4 flex items-center justify-between shadow-sm shrink-0" style="background-color: var(--color-surface);">
                            <span class="text-xs font-bold" style="color: var(--color-primary);">Laundry Logo</span>
                            <div class="flex space-x-3 text-[10px] font-semibold" style="color: var(--color-text);">
                                <span>Home</span>
                                <span>Services</span>
                                <span>Pricing</span>
                                <span>Contact</span>
                            </div>
                        </div>

                        <!-- Main Sections Renderer -->
                        <div class="flex-1 space-y-4 p-4 overflow-y-auto">
                            @forelse($sections as $sec)
                                @if($sec->is_visible)
                                    <div class="border rounded-2xl p-6 transition-all relative group {{ $selectedSectionId === $sec->id ? 'border-[var(--color-primary)] ring-2 ring-[var(--color-primary)]/10 shadow-md' : 'border-dashed border-slate-200 bg-white hover:border-slate-400' }}">
                                        
                                        <!-- Header indicators -->
                                        <div class="absolute -top-3 left-4 bg-slate-800 text-white text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded shadow">
                                            {{ $sec->section_type }}
                                        </div>

                                        <!-- Rendering Section Previews dynamically -->
                                        @if($sec->section_type === 'hero')
                                            <div class="space-y-3">
                                                @if(isset($sec->content['eyebrow']))
                                                    <span class="text-[9px] font-bold uppercase tracking-widest block" style="color: var(--color-accent);">{{ $sec->content['eyebrow'] }}</span>
                                                @endif
                                                <h1 class="text-lg font-bold leading-tight" style="color: var(--color-heading);">{{ $sec->content['title'] ?? 'Judul Utama' }}</h1>
                                                <p class="text-xs leading-relaxed">{{ $sec->content['description'] ?? 'Teks deskripsi.' }}</p>
                                                @if(isset($sec->content['button_text']) && $sec->content['button_text'])
                                                    <button class="px-4 py-2 text-xs font-bold text-white shadow-sm mt-2" style="background-color: var(--color-primary); border-radius: var(--border-radius);">
                                                        {{ $sec->content['button_text'] }}
                                                    </button>
                                                @endif
                                            </div>
                                        @elseif($sec->section_type === 'services')
                                            <div class="space-y-4 text-center">
                                                <h2 class="text-md font-bold" style="color: var(--color-heading);">{{ $sec->content['title'] ?? 'Layanan Kami' }}</h2>
                                                <p class="text-xs">{{ $sec->content['description'] ?? '' }}</p>
                                                
                                                <div class="grid grid-cols-2 gap-3 pt-2 text-left">
                                                    <div class="p-3 bg-slate-50 border rounded-xl" style="background-color: var(--color-surface); border-radius: var(--border-radius);">
                                                        <span class="block text-xs font-bold" style="color: var(--color-heading);">Cuci Kering Setrika</span>
                                                        <span class="block text-[10px] text-slate-400 mt-1">Layanan cuci pakaian kiloan bersih rapi.</span>
                                                    </div>
                                                    <div class="p-3 bg-slate-50 border rounded-xl" style="background-color: var(--color-surface); border-radius: var(--border-radius);">
                                                        <span class="block text-xs font-bold" style="color: var(--color-heading);">Cuci Satuan Bedcover</span>
                                                        <span class="block text-[10px] text-slate-400 mt-1">Layanan khusus bedcover tebal.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($sec->section_type === 'pricing')
                                            <div class="space-y-4 text-center">
                                                <h2 class="text-md font-bold" style="color: var(--color-heading);">{{ $sec->content['title'] ?? 'Daftar Paket Harga' }}</h2>
                                                <p class="text-xs">{{ $sec->content['description'] ?? '' }}</p>
                                                
                                                <div class="grid grid-cols-3 gap-3 pt-2 text-left">
                                                    @foreach($sec->content['items'] ?? [] as $item)
                                                        <div class="p-3 bg-slate-50 border rounded-xl flex flex-col justify-between" style="background-color: var(--color-surface); border-radius: var(--border-radius);">
                                                            <div>
                                                                <span class="block text-xs font-bold" style="color: var(--color-heading);">{{ $item['name'] }}</span>
                                                                <span class="block text-sm font-bold text-amber-500 mt-2">{{ $item['price'] }}<span class="text-[9px] font-normal text-slate-400">{{ $item['unit'] }}</span></span>
                                                            </div>
                                                            <span class="block text-[9px] text-[#8896A6] mt-2 border-t pt-2">{{ $item['features'] ?? '' }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @elseif($sec->section_type === 'testimonials')
                                            <div class="space-y-4 text-center">
                                                <h2 class="text-md font-bold" style="color: var(--color-heading);">{{ $sec->content['title'] ?? 'Testimonial Pelanggan' }}</h2>
                                                
                                                <div class="grid grid-cols-2 gap-3 text-left">
                                                    @foreach($sec->content['items'] ?? [] as $item)
                                                        <div class="p-3 bg-slate-50 border rounded-xl" style="background-color: var(--color-surface); border-radius: var(--border-radius);">
                                                            <div class="flex text-amber-400 text-xs">★★★★★</div>
                                                            <p class="text-[10px] italic mt-1" style="color: var(--color-text);">"{{ $item['text'] }}"</p>
                                                            <span class="block text-[10px] font-bold mt-2" style="color: var(--color-heading);">{{ $item['name'] }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @elseif($sec->section_type === 'faq')
                                            <div class="space-y-3">
                                                <h2 class="text-md font-bold text-center" style="color: var(--color-heading);">{{ $sec->content['title'] ?? 'Pertanyaan Populer' }}</h2>
                                                <div class="space-y-2">
                                                    @foreach($sec->content['items'] ?? [] as $item)
                                                        <div class="p-3 bg-slate-50 border rounded-xl" style="background-color: var(--color-surface); border-radius: var(--border-radius);">
                                                            <span class="block text-xs font-bold" style="color: var(--color-heading);">❓ {{ $item['question'] }}</span>
                                                            <p class="text-[10px] mt-1 border-t pt-1.5">{{ $item['answer'] }}</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @elseif($sec->section_type === 'location')
                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="space-y-2">
                                                    <h2 class="text-md font-bold" style="color: var(--color-heading);">{{ $sec->content['title'] ?? 'Lokasi & Kontak' }}</h2>
                                                    <p class="text-[10px] leading-relaxed">
                                                        🏠 <b>Alamat:</b> {{ $sec->content['address'] ?? '' }}<br>
                                                        📞 <b>Telepon:</b> {{ $sec->content['phone'] ?? '' }}<br>
                                                        ⏰ <b>Jam:</b> {{ $sec->content['hours'] ?? '' }}
                                                    </p>
                                                </div>
                                                <div class="bg-slate-100 rounded-xl h-24 flex items-center justify-center text-[10px] text-slate-400">
                                                    Peta Google Maps
                                                </div>
                                            </div>
                                        @elseif($sec->section_type === 'cta')
                                            <div class="p-6 bg-slate-50 rounded-2xl text-center space-y-3" style="background-color: var(--color-surface); border-radius: var(--border-radius);">
                                                <h2 class="text-md font-bold" style="color: var(--color-heading);">{{ $sec->content['title'] ?? 'Pesan Sekarang' }}</h2>
                                                <p class="text-xs">{{ $sec->content['text'] ?? '' }}</p>
                                                <button class="px-5 py-2 text-xs font-bold text-white shadow" style="background-color: var(--color-accent); border-radius: var(--border-radius);">
                                                    {{ $sec->content['button_text'] ?? 'WhatsApp' }}
                                                </button>
                                            </div>
                                        @endif

                                    </div>
                                @endif
                            @empty
                                <div class="h-64 flex items-center justify-center text-center text-slate-400">
                                    <p class="text-xs">Klik tombol **Tambah Bagian** untuk menyusun susunan konten landing page Anda.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Footer Preview -->
                        <div class="bg-slate-900 p-4 text-center text-[9px] text-slate-400 shrink-0">
                            <span>&copy; 2026 Laundry Promax. All rights reserved.</span>
                        </div>

                    </div>
                </div>
            </div>
        </main>

        <!-- Right Panel: Editor / Configuration Settings Form -->
        <aside class="w-96 bg-white border-l border-[#E2E7EF] flex flex-col shrink-0 overflow-y-auto">
            @if($selectedSectionId && $selectedSectionType)
                <div class="p-4 border-b border-[#E2E7EF] flex justify-between items-center bg-[#F8F9FC]">
                    <span class="text-xs font-bold text-[#4A5568] uppercase tracking-wider">Pengaturan Konten</span>
                    <span class="text-[10px] text-[#8896A6] font-mono">Tipe: {{ $selectedSectionType }}</span>
                </div>

                <div class="p-6 space-y-6 text-xs text-[#4A5568] font-medium">
                    <!-- CONTENT EDIT FIELDS -->
                    <div class="space-y-4">
                        @if($selectedSectionType === 'hero')
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider">Eyebrow Teks (Kecil)</label>
                                <input wire:model="editingContent.eyebrow" wire:keyup="updateSectionContent" type="text" class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider">Judul Utama (Heading)</label>
                                <input wire:model="editingContent.title" wire:keyup="updateSectionContent" type="text" class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider">Paragraf Deskripsi</label>
                                <textarea wire:model="editingContent.description" wire:keyup="updateSectionContent" rows="3" class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]"></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-semibold uppercase tracking-wider">Teks Tombol</label>
                                    <input wire:model="editingContent.button_text" wire:keyup="updateSectionContent" type="text" class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-semibold uppercase tracking-wider">URL Tombol</label>
                                    <input wire:model="editingContent.button_url" wire:keyup="updateSectionContent" type="text" class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]">
                                </div>
                            </div>
                        @elseif($selectedSectionType === 'services')
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider">Judul Section</label>
                                <input wire:model="editingContent.title" wire:keyup="updateSectionContent" type="text" class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider">Deskripsi Section</label>
                                <textarea wire:model="editingContent.description" wire:keyup="updateSectionContent" rows="2" class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]"></textarea>
                            </div>
                            <div class="pt-2">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input wire:model="editingContent.use_master_services" wire:change="updateSectionContent" type="checkbox" class="h-4 w-4 text-[#1E3A5F] border-[#E2E7EF] rounded">
                                    <span class="text-xs font-bold text-[#1A1D23]">Hubungkan Otomatis ke Master Layanan</span>
                                </label>
                            </div>
                        @elseif($selectedSectionType === 'pricing')
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider">Judul Section</label>
                                <input wire:model="editingContent.title" wire:keyup="updateSectionContent" type="text" class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]">
                            </div>
                            
                            <div class="border-t border-[#E2E7EF] pt-4 space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="block text-[10px] font-bold text-slate-800 uppercase tracking-wider">Daftar Paket</span>
                                    <button wire:click="addPricingItem" type="button" class="text-[10px] font-bold text-[#1E3A5F]">+ Tambah Paket</button>
                                </div>
                                
                                @foreach($editingContent['items'] ?? [] as $index => $item)
                                    <div class="p-3 bg-slate-50 border rounded-xl space-y-2 relative">
                                        <button wire:click="removePricingItem({{ $index }})" type="button" class="absolute top-2 right-2 text-rose-500 hover:text-rose-700">✕</button>
                                        
                                        <input wire:model="editingContent.items.{{ $index }}.name" wire:keyup="updateSectionContent" type="text" placeholder="Nama Paket" class="w-full px-2 py-1.5 border border-[#E2E7EF] bg-white rounded-lg text-xs">
                                        <div class="grid grid-cols-2 gap-2">
                                            <input wire:model="editingContent.items.{{ $index }}.price" wire:keyup="updateSectionContent" type="text" placeholder="Harga (ex: Rp 6.000)" class="w-full px-2 py-1.5 border border-[#E2E7EF] bg-white rounded-lg text-xs">
                                            <input wire:model="editingContent.items.{{ $index }}.unit" wire:keyup="updateSectionContent" type="text" placeholder="Satuan (ex: /kg)" class="w-full px-2 py-1.5 border border-[#E2E7EF] bg-white rounded-lg text-xs">
                                        </div>
                                        <input wire:model="editingContent.items.{{ $index }}.features" wire:keyup="updateSectionContent" type="text" placeholder="Fitur (pisahkan dengan koma)" class="w-full px-2 py-1.5 border border-[#E2E7EF] bg-white rounded-lg text-xs">
                                    </div>
                                @endforeach
                            </div>
                        @elseif($selectedSectionType === 'testimonials')
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider">Judul Section</label>
                                <input wire:model="editingContent.title" wire:keyup="updateSectionContent" type="text" class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]">
                            </div>
                            
                            <div class="border-t border-[#E2E7EF] pt-4 space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="block text-[10px] font-bold text-slate-800 uppercase tracking-wider">Daftar Testimonial</span>
                                    <button wire:click="addTestimonialItem" type="button" class="text-[10px] font-bold text-[#1E3A5F]">+ Tambah Testi</button>
                                </div>
                                
                                @foreach($editingContent['items'] ?? [] as $index => $item)
                                    <div class="p-3 bg-slate-50 border rounded-xl space-y-2 relative">
                                        <button wire:click="removeTestimonialItem({{ $index }})" type="button" class="absolute top-2 right-2 text-rose-500 hover:text-rose-700">✕</button>
                                        
                                        <input wire:model="editingContent.items.{{ $index }}.name" wire:keyup="updateSectionContent" type="text" placeholder="Nama Pelanggan" class="w-full px-2 py-1.5 border border-[#E2E7EF] bg-white rounded-lg text-xs">
                                        <textarea wire:model="editingContent.items.{{ $index }}.text" wire:keyup="updateSectionContent" placeholder="Isi Ulasan" rows="2" class="w-full px-2 py-1.5 border border-[#E2E7EF] bg-white rounded-lg text-xs"></textarea>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($selectedSectionType === 'faq')
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider">Judul Section</label>
                                <input wire:model="editingContent.title" wire:keyup="updateSectionContent" type="text" class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]">
                            </div>
                            
                            <div class="border-t border-[#E2E7EF] pt-4 space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="block text-[10px] font-bold text-slate-800 uppercase tracking-wider">Daftar FAQ</span>
                                    <button wire:click="addFaqItem" type="button" class="text-[10px] font-bold text-[#1E3A5F]">+ Tambah Tanya-Jawab</button>
                                </div>
                                
                                @foreach($editingContent['items'] ?? [] as $index => $item)
                                    <div class="p-3 bg-slate-50 border rounded-xl space-y-2 relative">
                                        <button wire:click="removeFaqItem({{ $index }})" type="button" class="absolute top-2 right-2 text-rose-500 hover:text-rose-700">✕</button>
                                        
                                        <input wire:model="editingContent.items.{{ $index }}.question" wire:keyup="updateSectionContent" type="text" placeholder="Pertanyaan" class="w-full px-2 py-1.5 border border-[#E2E7EF] bg-white rounded-lg text-xs">
                                        <textarea wire:model="editingContent.items.{{ $index }}.answer" wire:keyup="updateSectionContent" placeholder="Jawaban" rows="2" class="w-full px-2 py-1.5 border border-[#E2E7EF] bg-white rounded-lg text-xs"></textarea>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($selectedSectionType === 'location')
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider">Judul Section</label>
                                <input wire:model="editingContent.title" wire:keyup="updateSectionContent" type="text" class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider">Alamat Lengkap</label>
                                <input wire:model="editingContent.address" wire:keyup="updateSectionContent" type="text" class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider">Nomor Telepon/WA</label>
                                <input wire:model="editingContent.phone" wire:keyup="updateSectionContent" type="text" class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider">Jam Operasional</label>
                                <input wire:model="editingContent.hours" wire:keyup="updateSectionContent" type="text" placeholder="ex: Senin - Sabtu (08:00 - 20:00)" class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]">
                            </div>
                        @elseif($selectedSectionType === 'cta')
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider">Judul CTA Banner</label>
                                <input wire:model="editingContent.title" wire:keyup="updateSectionContent" type="text" class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider">Teks Banner</label>
                                <textarea wire:model="editingContent.text" wire:keyup="updateSectionContent" rows="3" class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]"></textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider">Teks Tombol WhatsApp</label>
                                <input wire:model="editingContent.button_text" wire:keyup="updateSectionContent" type="text" class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider">Nomor WhatsApp Tujuan</label>
                                <input wire:model="editingContent.whatsapp_number" wire:keyup="updateSectionContent" type="text" placeholder="Contoh: 628123456789" class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]">
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="h-full flex items-center justify-center p-6 text-center text-[#8896A6]">
                    <p class="text-xs">Pilih salah satu **Section** di panel kiri untuk mulai menyunting konten teks atau detail ulasan.</p>
                </div>
            @endif
        </aside>

    </div>

    <!-- Add Section Modal (Tailwind CSS Center Dialog Modal) -->
    @if($isAddSectionModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
            <div class="bg-white border border-[#E2E7EF] rounded-2xl max-w-lg w-full overflow-hidden shadow-2xl">
                <div class="p-6 border-b border-[#E2E7EF] flex justify-between items-center bg-[#F8F9FC]">
                    <h3 class="text-sm font-bold text-[#1A1D23] uppercase tracking-wider">Tambah Section Konten</h3>
                    <button wire:click="$set('isAddSectionModalOpen', false)" class="text-[#8896A6] hover:text-[#1A1D23]">✕</button>
                </div>

                <div class="p-6 grid grid-cols-2 gap-4 max-h-[400px] overflow-y-auto">
                    <!-- Hero Section -->
                    <button wire:click="addSection('hero', 'hero-01')" class="p-4 bg-slate-50 hover:bg-slate-100 border border-slate-200 hover:border-slate-400 rounded-xl text-left transition-all">
                        <span class="block text-xs font-bold text-slate-800">🚀 Hero Banner</span>
                        <span class="block text-[10px] text-slate-400 mt-1">Banner utama paling atas halaman web.</span>
                    </button>

                    <!-- Services Section -->
                    <button wire:click="addSection('services', 'services-01')" class="p-4 bg-slate-50 hover:bg-slate-100 border border-slate-200 hover:border-slate-400 rounded-xl text-left transition-all">
                        <span class="block text-xs font-bold text-slate-800">👕 Daftar Layanan</span>
                        <span class="block text-[10px] text-slate-400 mt-1">Daftar layanan laundry kiloan & satuan outlet.</span>
                    </button>

                    <!-- Pricing Section -->
                    <button wire:click="addSection('pricing', 'pricing-01')" class="p-4 bg-slate-50 hover:bg-slate-100 border border-slate-200 hover:border-slate-400 rounded-xl text-left transition-all">
                        <span class="block text-xs font-bold text-slate-800">💰 Daftar Harga</span>
                        <span class="block text-[10px] text-slate-400 mt-1">Tabel paket harga kustom laundry.</span>
                    </button>

                    <!-- Testimonials Section -->
                    <button wire:click="addSection('testimonials', 'testimonials-01')" class="p-4 bg-slate-50 hover:bg-slate-100 border border-slate-200 hover:border-slate-400 rounded-xl text-left transition-all">
                        <span class="block text-xs font-bold text-slate-800">⭐ Ulasan Pelanggan</span>
                        <span class="block text-[10px] text-slate-400 mt-1">Testimoni ulasan positif pelanggan laundry.</span>
                    </button>

                    <!-- FAQ Section -->
                    <button wire:click="addSection('faq', 'faq-01')" class="p-4 bg-slate-50 hover:bg-slate-100 border border-slate-200 hover:border-slate-400 rounded-xl text-left transition-all">
                        <span class="block text-xs font-bold text-slate-800">❓ FAQ (Tanya Jawab)</span>
                        <span class="block text-[10px] text-slate-400 mt-1">Kumpulan tanya-jawab seputar laundry.</span>
                    </button>

                    <!-- Location Section -->
                    <button wire:click="addSection('location', 'location-01')" class="p-4 bg-slate-50 hover:bg-slate-100 border border-slate-200 hover:border-slate-400 rounded-xl text-left transition-all">
                        <span class="block text-xs font-bold text-slate-800">📍 Lokasi & Kontak</span>
                        <span class="block text-[10px] text-slate-400 mt-1">Alamat outlet, telepon, jam buka, & map.</span>
                    </button>

                    <!-- CTA Section -->
                    <button wire:click="addSection('cta', 'cta-01')" class="p-4 bg-slate-50 hover:bg-slate-100 border border-slate-200 hover:border-slate-400 rounded-xl text-left transition-all">
                        <span class="block text-xs font-bold text-slate-800">💬 CTA WhatsApp</span>
                        <span class="block text-[10px] text-slate-400 mt-1">Tombol ajakan bertindak cepat order via WhatsApp.</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
