<?php

namespace App\Models;

use App\Models\Concerns\HasPublication;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, HasPublication;

    protected $fillable = [
        'category_id', 'author_id', 'title', 'slug', 'excerpt', 'content', 'cover_image',
        'status', 'is_featured', 'meta_title', 'meta_description', 'canonical_url',
        'social_image', 'noindex', 'published_at',
    ];

    protected function casts(): array
    {
        return ['is_featured' => 'boolean', 'noindex' => 'boolean', 'published_at' => 'datetime'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
