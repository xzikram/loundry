<?php

namespace App\Livewire\Tenant\Website;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Tenant\LandingMedia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Layout('layouts.tenant')]
class MediaLibraryPage extends Component
{
    use WithFileUploads;

    public $mediaFiles = [];
    public $uploads = []; // Temp uploaded files

    public string $search = '';
    public string $filterType = 'all'; // all, image, video

    // Details Modal fields
    public ?int $selectedMediaId = null;
    public string $altText = '';
    public string $title = '';
    public bool $isDetailModalOpen = false;

    protected array $rules = [
        'altText' => 'nullable|string|max:100',
        'title' => 'nullable|string|max:100',
    ];

    public function mount()
    {
        $this->loadMedia();
    }

    public function updatedUploads()
    {
        $this->validate([
            'uploads.*' => 'required|file|mimes:jpg,jpeg,png,webp,mp4,mov,avi|max:10240', // Max 10MB
        ]);

        foreach ($this->uploads as $file) {
            $originalName = $file->getClientOriginalName();
            $mime = $file->getMimeType();
            $size = $file->getSize();
            
            // Determine file type
            $type = str_contains($mime, 'video') ? 'video' : 'image';

            // Generate clean random file name
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::random(40) . '.' . $extension;

            // Tenant specific isolated folder path
            $tenantId = tenant('id');
            $folderPath = "tenants/{$tenantId}/landing-page/media";
            
            // Store file
            $path = $file->storeAs($folderPath, $fileName, 'public');
            $url = Storage::disk('public')->url($path);

            // Get width and height for images
            $width = null;
            $height = null;
            if ($type === 'image') {
                $dimensions = @getimagesize($file->getRealPath());
                if ($dimensions) {
                    $width = $dimensions[0];
                    $height = $dimensions[1];
                }
            }

            // Create record
            LandingMedia::create([
                'file_name' => $fileName,
                'original_name' => $originalName,
                'file_path' => $path,
                'file_url' => $url,
                'file_type' => $type,
                'mime_type' => $mime,
                'file_size' => $size,
                'width' => $width,
                'height' => $height,
                'title' => pathinfo($originalName, PATHINFO_FILENAME),
            ]);
        }

        // Clear temporary uploads array
        $this->uploads = [];
        $this->loadMedia();
        $this->dispatch('notify', 'File media berhasil diunggah!');
    }

    public function loadMedia()
    {
        $this->mediaFiles = LandingMedia::query()
            ->when($this->search, fn($q) => $q->where('original_name', 'like', '%' . $this->search . '%'))
            ->when($this->filterType !== 'all', fn($q) => $q->where('file_type', $this->filterType))
            ->latest()
            ->get();
    }

    public function searchMedia()
    {
        $this->loadMedia();
    }

    public function showDetails(int $id)
    {
        $media = LandingMedia::findOrFail($id);
        $this->selectedMediaId = $media->id;
        $this->altText = $media->alt_text ?? '';
        $this->title = $media->title ?? '';
        
        $this->isDetailModalOpen = true;
    }

    public function saveDetails()
    {
        $this->validate();

        if ($this->selectedMediaId) {
            $media = LandingMedia::findOrFail($this->selectedMediaId);
            $media->update([
                'alt_text' => $this->altText,
                'title' => $this->title,
            ]);
        }

        $this->isDetailModalOpen = false;
        $this->loadMedia();
        $this->dispatch('notify', 'Detail media berhasil disimpan!');
    }

    public function delete(int $id)
    {
        $media = LandingMedia::findOrFail($id);
        
        // Delete actual file from storage
        Storage::disk('public')->delete($media->file_path);

        $media->delete();
        
        if ($this->selectedMediaId === $id) {
            $this->isDetailModalOpen = false;
        }

        $this->loadMedia();
        $this->dispatch('notify', 'File media berhasil dihapus!');
    }

    public function render()
    {
        return view('livewire.tenant.website.media-library-page');
    }
}
