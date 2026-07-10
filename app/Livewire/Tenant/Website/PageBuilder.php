<?php

namespace App\Livewire\Tenant\Website;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Tenant\LandingPage;
use App\Models\Tenant\LandingSection;
use App\Models\Tenant\LandingThemeSetting;

#[Layout('layouts.blank')] // Use a blank layout for builder to take full screen space!
class PageBuilder extends Component
{
    public int $pageId;
    public LandingPage $page;
    public $sections = [];
    public $theme;

    // Viewport mode
    public string $viewportMode = 'desktop'; // desktop, tablet, mobile

    // Selection state
    public ?int $selectedSectionId = null;
    public string $selectedSectionType = '';
    public array $editingContent = [];
    public array $editingSettings = [];

    // Modal state
    public bool $isAddSectionModalOpen = false;

    // Save states
    public string $saveStatus = 'Saved'; // Saved, Saving, Unsaved

    public function mount(int $id)
    {
        $this->pageId = $id;
        $this->page = LandingPage::findOrFail($id);
        $this->theme = LandingThemeSetting::getSettings();
        $this->loadSections();
    }

    public function loadSections()
    {
        $this->sections = LandingSection::where('landing_page_id', $this->pageId)
            ->orderBy('sort_order')
            ->get();
    }

    public function selectSection(int $id)
    {
        if ($id === 0) {
            $this->selectedSectionId = 0;
            $this->selectedSectionType = 'header';
            $this->editingContent = $this->theme->custom_settings['header'] ?? [
                'logo_url' => '',
                'logo_width' => '120px',
                'business_name' => tenant('name') ?? 'Laundry',
                'sticky' => false,
                'cta_text' => 'Hubungi Kami',
                'cta_url' => '#location',
            ];
            $this->editingSettings = [];
            $this->saveStatus = 'Saved';
            return;
        }

        if ($id === -1) {
            $this->selectedSectionId = -1;
            $this->selectedSectionType = 'footer';
            $this->editingContent = $this->theme->custom_settings['footer'] ?? [
                'logo_url' => '',
                'description' => 'Layanan laundry premium terbaik.',
                'copyright' => '&copy; ' . date('Y') . ' ' . (tenant('name') ?? 'Laundry') . '. All rights reserved.',
            ];
            $this->editingSettings = [];
            $this->saveStatus = 'Saved';
            return;
        }

        $section = LandingSection::findOrFail($id);
        $this->selectedSectionId = $section->id;
        $this->selectedSectionType = $section->section_type;
        $this->editingContent = $section->content ?? [];
        $this->editingSettings = $section->settings ?? [];
        $this->saveStatus = 'Saved';
    }

    public function updateSectionContent()
    {
        $this->saveStatus = 'Saving...';
        
        if ($this->selectedSectionId === 0) {
            $customSettings = $this->theme->custom_settings ?? [];
            $customSettings['header'] = $this->editingContent;
            $this->theme->update(['custom_settings' => $customSettings]);
            $this->saveStatus = 'Saved';
            return;
        }

        if ($this->selectedSectionId === -1) {
            $customSettings = $this->theme->custom_settings ?? [];
            $customSettings['footer'] = $this->editingContent;
            $this->theme->update(['custom_settings' => $customSettings]);
            $this->saveStatus = 'Saved';
            return;
        }

        if ($this->selectedSectionId) {
            $section = LandingSection::findOrFail($this->selectedSectionId);
            $section->update([
                'content' => $this->editingContent,
            ]);
            $this->loadSections();
            $this->saveStatus = 'Saved';
        }
    }

    public function updateSectionSettings()
    {
        $this->saveStatus = 'Saving...';

        if ($this->selectedSectionId && $this->selectedSectionId !== 0 && $this->selectedSectionId !== -1) {
            $section = LandingSection::findOrFail($this->selectedSectionId);
            $section->update([
                'settings' => $this->editingSettings,
            ]);
            $this->loadSections();
            $this->saveStatus = 'Saved';
        }
    }

    public function moveUp(int $id)
    {
        $section = LandingSection::findOrFail($id);
        $previousSection = LandingSection::where('landing_page_id', $this->pageId)
            ->where('sort_order', '<', $section->sort_order)
            ->orderByDesc('sort_order')
            ->first();

        if ($previousSection) {
            $prevOrder = $previousSection->sort_order;
            $previousSection->update(['sort_order' => $section->sort_order]);
            $section->update(['sort_order' => $prevOrder]);
        }

        $this->loadSections();
        $this->dispatch('notify', 'Urutan section berhasil dipindahkan!');
    }

    public function moveDown(int $id)
    {
        $section = LandingSection::findOrFail($id);
        $nextSection = LandingSection::where('landing_page_id', $this->pageId)
            ->where('sort_order', '>', $section->sort_order)
            ->orderBy('sort_order')
            ->first();

        if ($nextSection) {
            $nextOrder = $nextSection->sort_order;
            $nextSection->update(['sort_order' => $section->sort_order]);
            $section->update(['sort_order' => $nextOrder]);
        }

        $this->loadSections();
        $this->dispatch('notify', 'Urutan section berhasil dipindahkan!');
    }

    public function toggleVisibility(int $id)
    {
        $section = LandingSection::findOrFail($id);
        $section->update(['is_visible' => !$section->is_visible]);
        
        $this->loadSections();
        $this->dispatch('notify', 'Status visibilitas section diperbarui!');
    }

    public function duplicateSection(int $id)
    {
        $section = LandingSection::findOrFail($id);
        
        LandingSection::create([
            'landing_page_id' => $section->landing_page_id,
            'section_type' => $section->section_type,
            'template_key' => $section->template_key,
            'content' => $section->content,
            'settings' => $section->settings,
            'sort_order' => $section->sort_order + 1,
            'is_visible' => $section->is_visible,
            'visibility_devices' => $section->visibility_devices,
        ]);

        $this->loadSections();
        $this->dispatch('notify', 'Section berhasil diduplikasi!');
    }

    public function deleteSection(int $id)
    {
        $section = LandingSection::findOrFail($id);
        $section->delete();
        
        if ($this->selectedSectionId === $id) {
            $this->selectedSectionId = null;
            $this->selectedSectionType = '';
        }

        $this->loadSections();
        $this->dispatch('notify', 'Section berhasil dihapus!');
    }

    public function openAddSectionModal()
    {
        $this->isAddSectionModalOpen = true;
    }

    public function addSection(string $type, string $templateKey)
    {
        // Get last order
        $lastSection = LandingSection::where('landing_page_id', $this->pageId)
            ->orderByDesc('sort_order')
            ->first();
        
        $order = $lastSection ? $lastSection->sort_order + 1 : 1;

        // Default content mapping
        $content = [];
        if ($type === 'hero') {
            $content = [
                'eyebrow' => 'NEW HERO SECTION',
                'title' => 'Judul Hero Baru Anda',
                'description' => 'Tuliskan deskripsi ringkas penawaran outlet laundry Anda di sini.',
                'button_text' => 'Hubungi Kami',
                'button_url' => '#',
            ];
        } elseif ($type === 'services') {
            $content = [
                'title' => 'Daftar Layanan Kami',
                'description' => 'Kami mencuci segala jenis pakaian dengan wangi tahan lama.',
                'use_master_services' => true,
            ];
        } elseif ($type === 'pricing') {
            $content = [
                'title' => 'Daftar Paket Harga',
                'description' => 'Harga hemat bersahabat.',
                'items' => [
                    ['name' => 'Cuci Kiloan', 'price' => 'Rp 6.000', 'unit' => '/kg', 'features' => 'Durasi 2 Hari, Rapi, Wangi'],
                    ['name' => 'Cuci Satuan Bedcover', 'price' => 'Rp 20.000', 'unit' => '/pcs', 'features' => 'Plastik pembungkus, Higienis'],
                ]
            ];
        } elseif ($type === 'testimonials') {
            $content = [
                'title' => 'Ulasan Pelanggan',
                'description' => 'Testimoni dari pelanggan setia.',
                'items' => [
                    ['name' => 'Budi Santoso', 'text' => 'Selesai tepat waktu dan wangi parfumnya enak banget.', 'rating' => 5],
                ]
            ];
        } elseif ($type === 'faq') {
            $content = [
                'title' => 'Pertanyaan Terbanyak',
                'items' => [
                    ['question' => 'Jam berapa outlet buka?', 'answer' => 'Outlet kami buka dari jam 08:00 sampai 21:00 setiap hari.'],
                ]
            ];
        } elseif ($type === 'location') {
            $content = [
                'title' => 'Hubungi Outlet Kami',
                'address' => 'Jl. Kebangsaan Raya No. 45',
                'phone' => '0812-3456-7890',
                'hours' => 'Senin - Minggu (08:00 - 21:00)',
                'map_iframe' => '',
            ];
        } elseif ($type === 'cta') {
            $content = [
                'title' => 'Siap Mencuci Hari Ini?',
                'text' => 'Klik tombol di bawah untuk memesan kurir jemput pakaian gratis sekarang juga!',
                'button_text' => 'Pesan Lewat WhatsApp',
                'whatsapp_number' => '',
                'whatsapp_message' => 'Halo, saya mau memesan laundry antar jemput.',
            ];
        } elseif ($type === 'video') {
            $content = [
                'title' => 'Video Review / Profil Laundry Kami',
                'video_type' => 'youtube', // youtube, direct
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'video_url' => '',
                'autoplay' => false,
                'muted' => true,
                'loop' => false,
            ];
        }

        $section = LandingSection::create([
            'landing_page_id' => $this->pageId,
            'section_type' => $type,
            'template_key' => $templateKey,
            'content' => $content,
            'settings' => [
                'bg_color' => '#FFFFFF',
                'text_color' => '#1A1D23',
                'padding_top' => 'medium',
                'padding_bottom' => 'medium',
            ],
            'sort_order' => $order,
            'is_visible' => true,
            'visibility_devices' => ['desktop' => true, 'tablet' => true, 'mobile' => true],
        ]);

        $this->isAddSectionModalOpen = false;
        $this->loadSections();
        $this->selectSection($section->id);
        $this->dispatch('notify', 'Section baru berhasil ditambahkan!');
    }

    // List items helpers for Pricing, Testimonials, FAQ
    public function addPricingItem()
    {
        $this->editingContent['items'][] = ['name' => 'Nama Paket', 'price' => 'Rp 0', 'unit' => '/kg', 'features' => 'Detail 1, Detail 2'];
        $this->updateSectionContent();
    }

    public function removePricingItem(int $index)
    {
        unset($this->editingContent['items'][$index]);
        $this->editingContent['items'] = array_values($this->editingContent['items']);
        $this->updateSectionContent();
    }

    public function addTestimonialItem()
    {
        $this->editingContent['items'][] = ['name' => 'Nama Pelanggan', 'text' => 'Tulis review di sini...', 'rating' => 5];
        $this->updateSectionContent();
    }

    public function removeTestimonialItem(int $index)
    {
        unset($this->editingContent['items'][$index]);
        $this->editingContent['items'] = array_values($this->editingContent['items']);
        $this->updateSectionContent();
    }

    public function addFaqItem()
    {
        $this->editingContent['items'][] = ['question' => 'Pertanyaan Baru', 'answer' => 'Jawaban dari pertanyaan...'];
        $this->updateSectionContent();
    }

    public function removeFaqItem(int $index)
    {
        unset($this->editingContent['items'][$index]);
        $this->editingContent['items'] = array_values($this->editingContent['items']);
        $this->updateSectionContent();
    }

    public function publishPage()
    {
        $this->page->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
        $this->dispatch('notify', 'Landing page berhasil diterbitkan secara publik!');
    }

    public function saveDraft()
    {
        $this->page->update([
            'status' => 'draft',
        ]);
        $this->dispatch('notify', 'Status halaman berhasil diubah ke Draft!');
    }

    public function render()
    {
        return view('livewire.tenant.website.page-builder');
    }
}
