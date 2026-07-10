<?php

namespace App\Livewire\Tenant\Website;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Tenant\LandingPage;
use Illuminate\Support\Str;

#[Layout('layouts.tenant')]
class PageList extends Component
{
    public $pages = [];

    // Form fields for creating/editing page properties
    public string $name = '';
    public string $slug = '';
    public string $pageType = 'regular_page'; // homepage, regular_page, campaign_page
    public string $status = 'draft'; // draft, published
    
    public ?int $editingPageId = null;
    public bool $isFormOpen = false;

    protected array $rules = [
        'name' => 'required|string|max:100',
        'slug' => 'required|string|max:100',
        'pageType' => 'required|in:homepage,regular_page,campaign_page',
        'status' => 'required|in:draft,published',
    ];

    public function mount()
    {
        $this->loadPages();
    }

    public function loadPages()
    {
        $this->pages = LandingPage::latest()->get();
    }

    public function updatedName()
    {
        if (!$this->editingPageId) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->isFormOpen = true;
    }

    public function resetForm()
    {
        $this->name = '';
        $this->slug = '';
        $this->pageType = 'regular_page';
        $this->status = 'draft';
        $this->editingPageId = null;
    }

    public function save()
    {
        // Force lowercase slug
        $this->slug = Str::slug($this->slug);
        
        $this->validate();

        // If page type is homepage, check if another exists
        $isHomepage = $this->pageType === 'homepage';

        if ($isHomepage) {
            // Check if slug is 'home' (standard for homepage)
            $this->slug = 'home';
        }

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'page_type' => $this->pageType,
            'status' => $this->status,
            'is_homepage' => $isHomepage,
        ];

        if ($isHomepage && $this->status === 'published') {
            $data['published_at'] = now();
            // Reset other homepages
            LandingPage::where('is_homepage', true)->update(['is_homepage' => false]);
        }

        if ($this->editingPageId) {
            $page = LandingPage::findOrFail($this->editingPageId);
            $page->update($data);
        } else {
            $page = LandingPage::create($data);
            
            // Seed a starter blank hero if it's a new page so they don't see a completely blank white screen
            $this->seedInitialHero($page->id);
        }

        $this->resetForm();
        $this->isFormOpen = false;
        $this->loadPages();
        $this->dispatch('notify', 'Halaman berhasil disimpan!');
    }

    protected function seedInitialHero(int $pageId)
    {
        \App\Models\Tenant\LandingSection::create([
            'landing_page_id' => $pageId,
            'section_type' => 'hero',
            'template_key' => 'hero-01',
            'sort_order' => 1,
            'content' => [
                'eyebrow' => 'SELAMAT DATANG',
                'title' => 'Judul Landing Page Baru Anda',
                'description' => 'Mulai edit halaman ini dengan menambahkan section-section baru melalui Page Builder.',
                'button_text' => 'Pelajari Selengkapnya',
                'button_url' => '#',
            ],
            'settings' => [
                'bg_color' => '#FFFFFF',
                'text_color' => '#1A1D23',
                'padding_top' => 'medium',
                'padding_bottom' => 'medium',
            ],
            'visibility_devices' => ['desktop' => true, 'tablet' => true, 'mobile' => true],
        ]);
    }

    public function editProperties(int $id)
    {
        $page = LandingPage::findOrFail($id);
        $this->editingPageId = $page->id;
        $this->name = $page->name;
        $this->slug = $page->slug;
        $this->pageType = $page->page_type;
        $this->status = $page->status;

        $this->isFormOpen = true;
    }

    public function delete(int $id)
    {
        $page = LandingPage::findOrFail($id);
        $page->delete();
        
        $this->loadPages();
        $this->dispatch('notify', 'Halaman berhasil dihapus!');
    }

    public function setAsHomepage(int $id)
    {
        // Reset all
        LandingPage::query()->update(['is_homepage' => false, 'page_type' => 'regular_page']);

        // Set selected
        $page = LandingPage::findOrFail($id);
        $page->update([
            'is_homepage' => true,
            'page_type' => 'homepage',
            'slug' => 'home',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->loadPages();
        $this->dispatch('notify', 'Halaman berhasil diatur sebagai beranda utama!');
    }

    public function render()
    {
        return view('livewire.tenant.website.page-list');
    }
}
