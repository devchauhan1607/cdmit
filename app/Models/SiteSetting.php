<?php

namespace App\Models;

use Database\Factories\SiteSettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    /** @use HasFactory<SiteSettingFactory> */
    use HasFactory;

    protected $fillable = ['key', 'value', 'type', 'group', 'is_public'];

    protected function casts(): array
    {
        return ['is_public' => 'boolean'];
    }

    public static function publicSettings(): array
    {
        return Cache::remember('site.public-settings', now()->addHour(), fn () => static::query()
            ->where('is_public', true)
            ->pluck('value', 'key')
            ->all());
    }

    public static function value(string $key, mixed $default = null): mixed
    {
        return Cache::remember("site.setting.{$key}", now()->addHour(), fn () => static::query()
            ->where('key', $key)
            ->value('value') ?? $default);
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::flush());
        static::deleted(fn () => Cache::flush());
    }
}
