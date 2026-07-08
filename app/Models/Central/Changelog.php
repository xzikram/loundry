<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

class Changelog extends Model
{
    protected $fillable = [
        'version',
        'title',
        'content',
        'type',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
