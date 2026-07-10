<div class="space-y-8 max-w-6xl">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-[#1A1D23]">Popup & Promosi</h1>
            <p class="text-sm text-[#8896A6] mt-1">Buat popup iklan, newsletter, atau modal promosi diskon laundry</p>
        </div>
        <button wire:click="openCreateForm" class="px-5 py-2.5 bg-gradient-to-r from-[#1E3A5F] to-[#2A5082] hover:from-[#2A5082] hover:to-[#1E3A5F] text-white rounded-xl text-xs font-bold shadow-lg shadow-[#1E3A5F]/10 transition-all cursor-pointer">
            + Buat Popup Campaign
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Campaigns List -->
        <div class="lg:col-span-7 space-y-4">
            <div class="bg-white border border-[#E2E7EF] rounded-2xl overflow-hidden shadow-sm">
                <table class="min-w-full divide-y divide-[#E2E7EF] text-left">
                    <thead class="bg-[#F8F9FC] text-xs font-bold text-[#4A5568] uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Nama Iklan</th>
                            <th class="px-6 py-4">Tipe</th>
                            <th class="px-6 py-4">Trigger</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E7EF] text-sm text-[#1A1D23]">
                        @forelse($popups as $pop)
                            <tr>
                                <td class="px-6 py-4 font-bold">
                                    {{ $pop->name }}
                                    <span class="block text-[10px] text-[#8896A6] font-normal mt-0.5">
                                        Tautan: {{ $pop->page ? $pop->page->name : 'Semua Halaman' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold capitalize">
                                    {{ str_replace('_', ' ', $pop->popup_type) }}
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-full font-medium capitalize">
                                        {{ $pop->trigger_type }} ({{ $pop->trigger_value ? $pop->trigger_value : 'N/A' }})
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button wire:click="toggleActive({{ $pop->id }})" class="px-3 py-1 rounded-full text-xs font-bold transition-all {{ $pop->is_active ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-rose-50 text-rose-500 border border-rose-100' }}">
                                        {{ $pop->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button wire:click="edit({{ $pop->id }})" class="text-xs font-bold text-[#1E3A5F] hover:text-[#2A5082]">
                                        Edit
                                    </button>
                                    <button onclick="confirm('Apakah Anda yakin ingin menghapus popup ini?') || event.stopImmediatePropagation()" wire:click="delete({{ $pop->id }})" class="text-xs font-bold text-rose-500 hover:text-rose-700">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-16 text-[#8896A6]">
                                    <svg class="mx-auto h-12 w-12 text-[#8896A6]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <h3 class="mt-2 text-sm font-bold text-[#1A1D23]">Belum ada popup promo</h3>
                                    <p class="mt-1 text-xs text-[#8896A6]">Aktifkan iklan popup diskon / promo untuk menaikkan penjualan laundry.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Form Builder Panel -->
        <div class="lg:col-span-5 space-y-6">
            @if($isFormOpen)
                <div class="bg-white border border-[#E2E7EF] rounded-2xl p-6 space-y-5 shadow-md">
                    <div class="border-b border-[#E2E7EF] pb-3 flex justify-between items-center">
                        <h3 class="text-md font-bold text-[#1A1D23]">{{ $editingPopupId ? 'Edit Popup' : 'Buat Popup Baru' }}</h3>
                        <button wire:click="$set('isFormOpen', false)" class="text-[#8896A6] hover:text-[#1A1D23]">✕</button>
                    </div>

                    <form wire:submit.prevent="save" class="space-y-4 text-xs font-medium text-[#4A5568]">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider">Nama Kampanye Iklan</label>
                            <input wire:model="name" type="text" placeholder="Contoh: Popup Diskon Lebaran" class="w-full mt-1.5 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none focus:border-[#1E3A5F]">
                            @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider">Tipe Tampilan</label>
                                <select wire:model="popupType" class="w-full mt-1.5 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none">
                                    <option value="center_modal">Modal Di Tengah</option>
                                    <option value="full_screen">Layar Penuh (Interstitial)</option>
                                    <option value="bottom_banner">Banner Melayang Bawah</option>
                                    <option value="slide_in_right">Geser Masuk Kanan</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider">Tautan Halaman</label>
                                <select wire:model="landingPageId" class="w-full mt-1.5 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none">
                                    <option value="">Semua Halaman</option>
                                    @foreach($pages as $pg)
                                        <option value="{{ $pg->id }}">{{ $pg->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-xl space-y-4 border border-slate-100">
                            <span class="block font-bold text-slate-800 text-[10px] uppercase tracking-wider">Konten Visual</span>
                            
                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider">Judul Banner/Promo</label>
                                <input wire:model="title" type="text" placeholder="Contoh: Diskon 20% Cuci Karpet!" class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-white text-[#1A1D23] rounded-lg text-xs focus:outline-none focus:border-[#1E3A5F]">
                                @error('title') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider">Deskripsi/Detail Promo</label>
                                <textarea wire:model="description" placeholder="Masukkan detail ketentuan promo di sini..." rows="3" class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-white text-[#1A1D23] rounded-lg text-xs focus:outline-none focus:border-[#1E3A5F]"></textarea>
                                @error('description') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-semibold uppercase tracking-wider">URL Gambar Banner</label>
                                <input wire:model="imageUrl" type="text" placeholder="Contoh: /storage/promos/karpet.jpg" class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-white text-[#1A1D23] rounded-lg text-xs focus:outline-none focus:border-[#1E3A5F]">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-semibold uppercase tracking-wider">Teks Tombol CTA</label>
                                    <input wire:model="buttonText" type="text" placeholder="Contoh: Ambil Kupon" class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-white text-[#1A1D23] rounded-lg text-xs focus:outline-none focus:border-[#1E3A5F]">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-semibold uppercase tracking-wider">Tautan Tombol CTA</label>
                                    <input wire:model="buttonUrl" type="text" placeholder="Contoh: /register, https://..." class="w-full mt-1.5 px-3 py-2 border border-[#E2E7EF] bg-white text-[#1A1D23] rounded-lg text-xs focus:outline-none focus:border-[#1E3A5F]">
                                </div>
                            </div>
                        </div>

                        <!-- Triggers & Frequency -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider">Trigger</label>
                                <select wire:model.live="triggerType" class="w-full mt-1.5 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none">
                                    <option value="immediately">Langsung Tampil</option>
                                    <option value="delay">Jeda Waktu (Detik)</option>
                                    <option value="scroll">Scroll Halaman (%)</option>
                                    <option value="exit_intent">Ingin Keluar Tab</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider">Frekuensi Tampil</label>
                                <select wire:model="frequencyType" class="w-full mt-1.5 px-4 py-2.5 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none">
                                    <option value="every_visit">Setiap Kunjungan</option>
                                    <option value="once_per_session">Sekali Per Sesi</option>
                                    <option value="once_per_day">Sekali Sehari</option>
                                    <option value="once_per_week">Sekali Seminggu</option>
                                    <option value="only_once">Hanya Sekali Selamanya</option>
                                </select>
                            </div>
                        </div>

                        @if($triggerType === 'delay')
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider">Jeda Tampil (Detik)</label>
                                <input wire:model="delaySeconds" type="number" min="1" max="60" class="w-full mt-1.5 px-4 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none">
                            </div>
                        @elseif($triggerType === 'scroll')
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider">Batas Scroll Halaman (%)</label>
                                <input wire:model="scrollPercent" type="number" min="5" max="95" class="w-full mt-1.5 px-4 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none">
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider">Tanggal Mulai</label>
                                <input wire:model="startAt" type="datetime-local" class="w-full mt-1.5 px-4 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider">Tanggal Selesai</label>
                                <input wire:model="endAt" type="datetime-local" class="w-full mt-1.5 px-4 py-2 border border-[#E2E7EF] bg-[#F8F9FC] text-[#1A1D23] rounded-xl text-xs focus:outline-none">
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-4 border-t border-[#E2E7EF]">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input wire:model="isActive" type="checkbox" class="h-4 w-4 text-[#1E3A5F] border-[#E2E7EF] rounded">
                                <span class="text-xs font-bold text-[#1A1D23]">Aktifkan Sekarang</span>
                            </label>
                            
                            <div class="flex space-x-2">
                                <button type="button" wire:click="$set('isFormOpen', false)" class="px-4 py-2 text-xs font-semibold border border-[#E2E7EF] text-[#4A5568] rounded-xl hover:bg-[#F8F9FC]">
                                    Batal
                                </button>
                                <button type="submit" class="px-5 py-2 bg-[#1E3A5F] hover:bg-[#2A5082] text-white rounded-xl text-xs font-bold shadow-md">
                                    Simpan Popup
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-[#F8F9FC] border border-dashed border-[#E2E7EF] rounded-2xl p-8 text-center text-[#8896A6]">
                    <p class="text-xs">Klik tombol **Buat Popup Campaign** untuk memicu modal promosi atau banner melayang pada halaman pengunjung secara terjadwal.</p>
                </div>
            @endif
        </div>
    </div>
</div>
