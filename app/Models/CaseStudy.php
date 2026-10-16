<?php

namespace App\Models;

use App\Models\Concerns\HasPublication;
use Database\Factories\CaseStudyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CaseStudy extends Model
{
    /** @use HasFactory<CaseStudyFactory> */
    use HasFactory, HasPublication;

    protected $fillable = [
        'title', 'slug', 'client_name', 'industry', 'summary', 'overview', 'challenge',
        'solution', 'implementation', 'results', 'project_url', 'cover_image', 'screenshots',
        'status', 'is_featured', 'display_order', 'meta_title', 'meta_description',
        'canonical_url', 'noindex', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'results' => 'array', 'screenshots' => 'array', 'is_featured' => 'boolean',
            'noindex' => 'boolean', 'published_at' => 'datetime',
        ];
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class);
    }
}
