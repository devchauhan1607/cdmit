<?php

namespace App\Models;

use Database\Factories\PageSeoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSeo extends Model
{
    /** @use HasFactory<PageSeoFactory> */
    use HasFactory;

    protected $fillable = [
        'page_key', 'title', 'description', 'canonical_url', 'social_title',
        'social_description', 'social_image', 'noindex', 'structured_data',
    ];

    protected function casts(): array
    {
        return ['noindex' => 'boolean', 'structured_data' => 'array'];
    }
}
