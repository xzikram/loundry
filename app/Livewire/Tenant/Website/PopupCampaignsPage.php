<?php

namespace App\Livewire\Tenant\Website;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Tenant\PopupCampaign;
use App\Models\Tenant\LandingPage;

#[Layout('layouts.tenant')]
class PopupCampaignsPage extends Component
{
    public $popups = [];
    public $pages = [];

    // Form fields
    public string $name = '';
    public string $popupType = 'center_modal';
    public ?int $landingPageId = null;
    
    // Content JSON
    public string $title = '';
    public string $description = '';
    public string $imageUrl = '';
    public string $buttonText = '';
    public string $buttonUrl = '';

    // Settings JSON
    public string $bgColor = '#FFFFFF';
    public string $textColor = '#1A1D23';
    public int $delaySeconds = 3;
    public int $scrollPercent = 30;

    // Trigger & Frequency
    public string $triggerType = 'delay';
    public string $triggerValue = '';
    public string $frequencyType = 'once_per_session';
    
    public ?string $startAt = null;
    public ?string $endAt = null;
    public bool $isActive = true;

    public ?int $editingPopupId = null;
    public bool $isFormOpen = false;

    protected array $rules = [
        'name' => 'required|string|max:100',
        'popupType' => 'required|in:center_modal,full_screen,bottom_banner,slide_in_right',
        'landingPageId' => 'nullable|integer',
        'title' => 'required|string|max:150',
        'description' => 'required|string|max:500',
        'imageUrl' => 'nullable|string|max:255',
        'buttonText' => 'nullable|string|max:30',
        'buttonUrl' => 'nullable|string|max:255',
        'bgColor' => 'required|string|max:7',
        'textColor' => 'required|string|max:7',
        'triggerType' => 'required|in:immediately,delay,scroll,exit_intent',
        'frequencyType' => 'required|in:every_visit,once_per_session,once_per_day,once_per_week,only_once',
        'startAt' => 'nullable|date',
        'endAt' => 'nullable|date|after_or_equal:startAt',
        'isActive' => 'required|boolean',
    ];

    public function mount()
    {
        $this->loadPopups();
        $this->pages = LandingPage::latest()->get();
    }

    public function loadPopups()
    {
        $this->popups = PopupCampaign::with('page')->latest()->get();
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->isFormOpen = true;
    }

    public function resetForm()
    {
        $this->name = '';
        $this->popupType = 'center_modal';
        $this->landingPageId = null;
        $this->title = '';
        $this->description = '';
        $this->imageUrl = '';
        $this->buttonText = '';
        $this->buttonUrl = '';
        $this->bgColor = '#FFFFFF';
        $this->textColor = '#1A1D23';
        $this->delaySeconds = 3;
        $this->scrollPercent = 30;
        $this->triggerType = 'delay';
        $this->triggerValue = '';
        $this->frequencyType = 'once_per_session';
        $this->startAt = null;
        $this->endAt = null;
        $this->isActive = true;
        $this->editingPopupId = null;
    }

    public function save()
    {
        // Populate trigger value based on type
        if ($this->triggerType === 'delay') {
            $this->triggerValue = (string)$this->delaySeconds;
        } elseif ($this->triggerType === 'scroll') {
            $this->triggerValue = (string)$this->scrollPercent;
        } else {
            $this->triggerValue = '';
        }

        $this->validate();

        $content = [
            'title' => $this->title,
            'description' => $this->description,
            'image_url' => $this->imageUrl,
            'button_text' => $this->buttonText,
            'button_url' => $this->buttonUrl,
        ];

        $settings = [
            'bg_color' => $this->bgColor,
            'text_color' => $this->textColor,
        ];

        $data = [
            'landing_page_id' => $this->landingPageId,
            'name' => $this->name,
            'popup_type' => $this->popupType,
            'content' => $content,
            'settings' => $settings,
            'trigger_type' => $this->triggerType,
            'trigger_value' => $this->triggerValue,
            'frequency_type' => $this->frequencyType,
            'start_at' => $this->startAt ? date('Y-m-d H:i:s', strtotime($this->startAt)) : null,
            'end_at' => $this->endAt ? date('Y-m-d H:i:s', strtotime($this->endAt)) : null,
            'is_active' => $this->isActive,
        ];

        if ($this->editingPopupId) {
            $popup = PopupCampaign::findOrFail($this->editingPopupId);
            $popup->update($data);
        } else {
            PopupCampaign::create($data);
        }

        $this->resetForm();
        $this->isFormOpen = false;
        $this->loadPopups();
        $this->dispatch('notify', 'Popup promosi berhasil disimpan!');
    }

    public function edit(int $id)
    {
        $popup = PopupCampaign::findOrFail($id);
        $this->editingPopupId = $popup->id;
        $this->name = $popup->name;
        $this->popupType = $popup->popup_type;
        $this->landingPageId = $popup->landing_page_id;
        
        $this->title = $popup->content['title'] ?? '';
        $this->description = $popup->content['description'] ?? '';
        $this->imageUrl = $popup->content['image_url'] ?? '';
        $this->buttonText = $popup->content['button_text'] ?? '';
        $this->buttonUrl = $popup->content['button_url'] ?? '';
        
        $this->bgColor = $popup->settings['bg_color'] ?? '#FFFFFF';
        $this->textColor = $popup->settings['text_color'] ?? '#1A1D23';
        
        $this->triggerType = $popup->trigger_type;
        if ($this->triggerType === 'delay') {
            $this->delaySeconds = (int)$popup->trigger_value;
        } elseif ($this->triggerType === 'scroll') {
            $this->scrollPercent = (int)$popup->trigger_value;
        }
        
        $this->frequencyType = $popup->frequency_type;
        $this->startAt = $popup->start_at ? $popup->start_at->format('Y-m-d\TH:i') : null;
        $this->endAt = $popup->end_at ? $popup->end_at->format('Y-m-d\TH:i') : null;
        $this->isActive = $popup->is_active;

        $this->isFormOpen = true;
    }

    public function delete(int $id)
    {
        $popup = PopupCampaign::findOrFail($id);
        $popup->delete();
        
        $this->loadPopups();
        $this->dispatch('notify', 'Popup campaign berhasil dihapus!');
    }

    public function toggleActive(int $id)
    {
        $popup = PopupCampaign::findOrFail($id);
        $popup->update(['is_active' => !$popup->is_active]);
        
        $this->loadPopups();
        $this->dispatch('notify', 'Status popup diperbarui!');
    }

    public function render()
    {
        return view('livewire.tenant.website.popup-campaigns-page');
    }
}
