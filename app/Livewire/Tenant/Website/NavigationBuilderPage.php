<?php

namespace App\Livewire\Tenant\Website;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Tenant\NavigationMenu;

#[Layout('layouts.tenant')]
class NavigationBuilderPage extends Component
{
    public $menus = [];
    public string $menuType = 'header'; // header, footer

    // Form fields
    public string $label = '';
    public string $url = '';
    public string $target = '_self';
    public int $sortOrder = 0;
    public ?int $parentId = null;
    public ?int $editingMenuId = null;

    public bool $isFormOpen = false;

    protected array $rules = [
        'label' => 'required|string|max:50',
        'url' => 'required|string|max:255',
        'target' => 'required|in:_self,_blank',
        'sortOrder' => 'required|integer|min:0',
        'parentId' => 'nullable|integer',
    ];

    public function mount()
    {
        $this->loadMenus();
    }

    public function loadMenus()
    {
        $this->menus = NavigationMenu::where('menu_type', $this->menuType)
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('sort_order')
            ->get();
    }

    public function switchTab(string $type)
    {
        $this->menuType = $type;
        $this->parentId = null;
        $this->editingMenuId = null;
        $this->isFormOpen = false;
        $this->loadMenus();
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->isFormOpen = true;
    }

    public function resetForm()
    {
        $this->label = '';
        $this->url = '';
        $this->target = '_self';
        $this->sortOrder = 0;
        $this->parentId = null;
        $this->editingMenuId = null;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingMenuId) {
            $menu = NavigationMenu::findOrFail($this->editingMenuId);
            $menu->update([
                'label' => $this->label,
                'url' => $this->url,
                'target' => $this->target,
                'sort_order' => $this->sortOrder,
                'parent_id' => $this->parentId,
            ]);
        } else {
            NavigationMenu::create([
                'label' => $this->label,
                'url' => $this->url,
                'target' => $this->target,
                'sort_order' => $this->sortOrder,
                'parent_id' => $this->parentId,
                'menu_type' => $this->menuType,
                'status' => 'active',
            ]);
        }

        $this->resetForm();
        $this->isFormOpen = false;
        $this->loadMenus();
        $this->dispatch('notify', 'Menu navigasi berhasil disimpan!');
    }

    public function edit(int $id)
    {
        $menu = NavigationMenu::findOrFail($id);
        $this->editingMenuId = $menu->id;
        $this->label = $menu->label;
        $this->url = $menu->url;
        $this->target = $menu->target;
        $this->sortOrder = $menu->sort_order;
        $this->parentId = $menu->parent_id;
        
        $this->isFormOpen = true;
    }

    public function delete(int $id)
    {
        $menu = NavigationMenu::findOrFail($id);
        $menu->delete();
        $this->loadMenus();
        $this->dispatch('notify', 'Menu navigasi berhasil dihapus!');
    }

    public function moveUp(int $id)
    {
        $menu = NavigationMenu::findOrFail($id);
        if ($menu->sort_order > 0) {
            $menu->decrement('sort_order');
            $this->loadMenus();
        }
    }

    public function moveDown(int $id)
    {
        $menu = NavigationMenu::findOrFail($id);
        $menu->increment('sort_order');
        $this->loadMenus();
    }

    public function render()
    {
        // Get potential parent menus (only top level and not the menu itself)
        $parentMenus = NavigationMenu::where('menu_type', $this->menuType)
            ->whereNull('parent_id')
            ->when($this->editingMenuId, fn($q) => $q->where('id', '!=', $this->editingMenuId))
            ->get();

        return view('livewire.tenant.website.navigation-builder-page', compact('parentMenus'));
    }
}
