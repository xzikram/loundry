<?php

namespace App\Livewire\Tenant\Website;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Tenant\LandingPage;
use App\Models\Tenant\LandingMedia;
use App\Models\Tenant\PopupCampaign;

#[Layout('layouts.tenant')]
class WebsiteDashboard extends Component
{
    public ?LandingPage $homepage = null;
    public int $totalPagesCount = 0;
    public int $totalMediaCount = 0;
    public int $activePopupsCount = 0;
    public string $siteUrl = '';

    public function mount()
    {
        $this->homepage = LandingPage::where('is_homepage', true)->first();
        $this->totalPagesCount = LandingPage::count();
        $this->totalMediaCount = LandingMedia::count();
        $this->activePopupsCount = PopupCampaign::where('is_active', true)->count();

        // Build dynamic site URL based on production vs local environment separators
        $host = request()->getHost();
        $isProduction = str_contains($host, 'jarvisid.com');
        $separator = $isProduction ? '-' : '.';
        $scheme = request()->getScheme();
        $baseDomain = $isProduction ? 'clean.jarvisid.com' : 'laundrypromax.test';
        
        $this->siteUrl = $scheme . '://' . tenant('id') . $separator . $baseDomain;
    }

    public function createHomepage()
    {
        // Check if homepage already exists
        if ($this->homepage) {
            return redirect()->route('tenant.website.builder', ['id' => $this->homepage->id]);
        }

        // Create initial default homepage
        $page = LandingPage::create([
            'name' => 'Halaman Beranda Utama',
            'slug' => 'home',
            'page_type' => 'homepage',
            'status' => 'draft',
            'is_homepage' => true,
        ]);

        // Seed with starter sections representing Template 1 — Laundry Modern
        $this->seedStarterSections($page->id);

        return redirect()->route('tenant.website.builder', ['id' => $page->id]);
    }

    protected function seedStarterSections(int $pageId)
    {
        // 1. Hero Section
        \App\Models\Tenant\LandingSection::create([
            'landing_page_id' => $pageId,
            'section_type' => 'hero',
            'template_key' => 'hero-01',
            'sort_order' => 1,
            'content' => [
                'eyebrow' => 'LAUNDRY PROMAX INDONESIA',
                'title' => 'Laundry Bersih, Wangi dan Tepat Waktu',
                'description' => 'Layanan laundry profesional dengan sistem antar-jemput kilat langsung ke rumah Anda.',
                'button_text' => 'Hubungi WhatsApp',
                'button_url' => 'https://wa.me/628123456789',
                'image_url' => '',
            ],
            'settings' => [
                'bg_color' => '#FFFFFF',
                'text_color' => '#1A1D23',
                'padding_top' => 'medium',
                'padding_bottom' => 'medium',
            ],
            'visibility_devices' => ['desktop' => true, 'tablet' => true, 'mobile' => true],
        ]);

        // 2. Tracking / Order Search Section
        \App\Models\Tenant\LandingSection::create([
            'landing_page_id' => $pageId,
            'section_type' => 'tracking',
            'template_key' => 'tracking-01',
            'sort_order' => 2,
            'content' => [
                'title' => 'Lacak Pesanan Laundry Anda',
                'description' => 'Masukkan nomor invoice untuk melihat status terkini pesanan laundry Anda secara real-time.',
            ],
            'settings' => [
                'bg_color' => '#F8F9FC',
                'text_color' => '#1A1D23',
                'padding_top' => 'medium',
                'padding_bottom' => 'medium',
            ],
            'visibility_devices' => ['desktop' => true, 'tablet' => true, 'mobile' => true],
        ]);

        // 3. Services Section
        \App\Models\Tenant\LandingSection::create([
            'landing_page_id' => $pageId,
            'section_type' => 'services',
            'template_key' => 'services-01',
            'sort_order' => 3,
            'content' => [
                'title' => 'Layanan Unggulan Kami',
                'description' => 'Kami menyediakan berbagai jenis paket cuci strika terbaik untuk kebutuhan pakaian Anda.',
                'use_master_services' => true, // Fetch automatically from existing master services!
            ],
            'settings' => [
                'bg_color' => '#F8F9FC',
                'text_color' => '#1A1D23',
                'padding_top' => 'medium',
                'padding_bottom' => 'medium',
            ],
            'visibility_devices' => ['desktop' => true, 'tablet' => true, 'mobile' => true],
        ]);

        // 4. Pricing Section
        \App\Models\Tenant\LandingSection::create([
            'landing_page_id' => $pageId,
            'section_type' => 'pricing',
            'template_key' => 'pricing-01',
            'sort_order' => 4,
            'content' => [
                'title' => 'Daftar Harga Kiloan / Satuan',
                'description' => 'Harga hemat bersahabat dengan jaminan kebersihan pakaian teruji.',
                'items' => [
                    ['name' => 'Paket Cuci Kering Setrika', 'price' => 'Rp 7.000', 'unit' => '/kg', 'features' => 'Parfum premium, Durasi 2 Hari, Setrika rapi'],
                    ['name' => 'Paket Express 24 Jam', 'price' => 'Rp 12.000', 'unit' => '/kg', 'features' => 'Selesai 1 Hari, Wangi tahan lama, Higienis'],
                    ['name' => 'Paket Cuci Satuan Jas / Bedcover', 'price' => 'Rp 25.000', 'unit' => '/pcs', 'features' => 'Cuci khusus manual, Plastik pelindung, Perawatan serat kain'],
                ]
            ],
            'settings' => [
                'bg_color' => '#FFFFFF',
                'text_color' => '#1A1D23',
                'padding_top' => 'medium',
                'padding_bottom' => 'medium',
            ],
            'visibility_devices' => ['desktop' => true, 'tablet' => true, 'mobile' => true],
        ]);

        // 5. Testimonials Section
        \App\Models\Tenant\LandingSection::create([
            'landing_page_id' => $pageId,
            'section_type' => 'testimonials',
            'template_key' => 'testimonials-01',
            'sort_order' => 5,
            'content' => [
                'title' => 'Apa Kata Pelanggan Kami?',
                'description' => 'Testimoni jujur dari pelanggan setia yang telah menggunakan layanan laundry kami.',
                'items' => [
                    ['name' => 'Rina Wijaya', 'text' => 'Suka banget cuci di sini, bajunya rapi sekali dan wangi parfumnya tahan lama berhari-hari.', 'rating' => 5],
                    ['name' => 'Budi Santoso', 'text' => 'Layanan kilat 24 jam sangat membantu saat ada urusan dinas mendadak. Sangat direkomendasikan!', 'rating' => 5],
                ]
            ],
            'settings' => [
                'bg_color' => '#F8F9FC',
                'text_color' => '#1A1D23',
                'padding_top' => 'medium',
                'padding_bottom' => 'medium',
            ],
            'visibility_devices' => ['desktop' => true, 'tablet' => true, 'mobile' => true],
        ]);

        // 6. FAQ Section
        \App\Models\Tenant\LandingSection::create([
            'landing_page_id' => $pageId,
            'section_type' => 'faq',
            'template_key' => 'faq-01',
            'sort_order' => 6,
            'content' => [
                'title' => 'Pertanyaan yang Sering Diajukan (FAQ)',
                'items' => [
                    ['question' => 'Apakah ada minimal order antar-jemput?', 'answer' => 'Ya, untuk layanan antar-jemput gratis minimal berat pakaian adalah 5 kg.'],
                    ['question' => 'Berapa lama estimasi pengerjaan laundry?', 'answer' => 'Paket Reguler memakan waktu 2 hari, sedangkan Paket Express selesai dalam waktu 24 jam.'],
                ]
            ],
            'settings' => [
                'bg_color' => '#FFFFFF',
                'text_color' => '#1A1D23',
                'padding_top' => 'medium',
                'padding_bottom' => 'medium',
            ],
            'visibility_devices' => ['desktop' => true, 'tablet' => true, 'mobile' => true],
        ]);

        // 7. Location / Contact Section
        \App\Models\Tenant\LandingSection::create([
            'landing_page_id' => $pageId,
            'section_type' => 'location',
            'template_key' => 'location-01',
            'sort_order' => 7,
            'content' => [
                'title' => 'Lokasi Outlet & Hubungi Kami',
                'address' => 'Jl. Kebangsaan Raya No. 45, Jakarta Selatan',
                'phone' => '0812-3456-7890',
                'hours' => 'Senin - Minggu (08:00 - 21:00)',
                'map_iframe' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15865.733618197775!2d106.827153!3d-6.175392!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e160e263c9%3A0x2a0d1e57c667085!2sMonumen%20Nasional!5e0!3m2!1sid!2sid!4v1625900000000!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
            ],
            'settings' => [
                'bg_color' => '#F8F9FC',
                'text_color' => '#1A1D23',
                'padding_top' => 'medium',
                'padding_bottom' => 'medium',
            ],
            'visibility_devices' => ['desktop' => true, 'tablet' => true, 'mobile' => true],
        ]);
    }

    public function render()
    {
        return view('livewire.tenant.website.website-dashboard');
    }
}
