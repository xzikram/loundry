<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class LandingMedia extends Model
{
    protected $table = 'landing_media';

    protected $fillable = [
        'file_name',
        'original_name',
        'file_path',
        'file_url',
        'file_type',
        'mime_type',
        'file_size',
        'width',
        'height',
        'alt_text',
        'title',
    ];
}
