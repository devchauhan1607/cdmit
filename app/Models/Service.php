<?php

namespace App\Models;

use App\Models\Concerns\HasPublication;
use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use HasFactory, HasPublication;

    protected $fillable = [
        'category_id', 'title', 'slug', 'eyebrow', 'summary', 'overview', 'benefits',
        'deliverables', 'process', 'technologies', 'icon', 'cover_image', 'status',
        'is_featured', 'display_order', 'meta_title', 'meta_description', 'canonical_url',
        'noindex', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'benefits' => 'array', 'deliverables' => 'array', 'process' => 'array',
            'technologies' => 'array', 'is_featured' => 'boolean', 'noindex' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
