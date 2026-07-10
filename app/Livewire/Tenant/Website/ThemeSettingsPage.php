<?php

namespace App\Livewire\Tenant\Website;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Tenant\LandingThemeSetting;

#[Layout('layouts.tenant')]
class ThemeSettingsPage extends Component
{
    public string $primaryColor = '#1E3A5F';
    public string $secondaryColor = '#2A5082';
    public string $accentColor = '#D4A853';
    public string $backgroundColor = '#F8F9FC';
    public string $surfaceColor = '#FFFFFF';
    public string $textColor = '#4A5568';
    public string $headingColor = '#1A1D23';
    public string $headingFont = 'Outfit';
    public string $bodyFont = 'Outfit';
    public string $buttonStyle = 'rounded-xl';
    public string $borderRadius = '12px';
    public string $containerWidth = 'max-w-6xl';
    public bool $saved = false;

    public function mount()
    {
        $settings = LandingThemeSetting::getSettings();
        
        $this->primaryColor = $settings->primary_color;
        $this->secondaryColor = $settings->secondary_color;
        $this->accentColor = $settings->accent_color;
        $this->backgroundColor = $settings->background_color;
        $this->surfaceColor = $settings->surface_color;
        $this->textColor = $settings->text_color;
        $this->headingColor = $settings->heading_color;
        $this->headingFont = $settings->heading_font;
        $this->bodyFont = $settings->body_font;
        $this->buttonStyle = $settings->button_style;
        $this->borderRadius = $settings->border_radius;
        $this->containerWidth = $settings->container_width;
    }

    public function save()
    {
        $this->validate([
            'primaryColor' => 'required|string|max:7',
            'secondaryColor' => 'required|string|max:7',
            'accentColor' => 'required|string|max:7',
            'backgroundColor' => 'required|string|max:7',
            'surfaceColor' => 'required|string|max:7',
            'textColor' => 'required|string|max:7',
            'headingColor' => 'required|string|max:7',
        ]);

        $settings = LandingThemeSetting::getSettings();
        $settings->update([
            'primary_color' => $this->primaryColor,
            'secondary_color' => $this->secondaryColor,
            'accent_color' => $this->accentColor,
            'background_color' => $this->backgroundColor,
            'surface_color' => $this->surfaceColor,
            'text_color' => $this->textColor,
            'heading_color' => $this->headingColor,
            'heading_font' => $this->headingFont,
            'body_font' => $this->bodyFont,
            'button_style' => $this->buttonStyle,
            'border_radius' => $this->borderRadius,
            'container_width' => $this->containerWidth,
        ]);

        $this->saved = true;
        $this->dispatch('notify', 'Pengaturan tema berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.tenant.website.theme-settings-page');
    }
}
