<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\SlugRedirect;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class ContentManager
{
    public function prepare(array $data, string $folder): array
    {
        foreach (['is_featured', 'noindex'] as $boolean) {
            if (array_key_exists($boolean, $data)) {
                $data[$boolean] = filter_var($data[$boolean], FILTER_VALIDATE_BOOLEAN);
            }
        }

        if (($data['status'] ?? null) === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        foreach (['cover_image', 'social_image'] as $field) {
            if (($data[$field] ?? null) instanceof UploadedFile) {
                $data[$field] = $data[$field]->store($folder, 'public');
            } elseif (array_key_exists($field, $data)) {
                unset($data[$field]);
            }
        }

        return $data;
    }

    public function syncSlug(Model $model, ?string $oldSlug, string $type): void
    {
        if ($oldSlug && $oldSlug !== $model->getAttribute('slug')) {
            SlugRedirect::updateOrCreate(
                ['type' => $type, 'old_slug' => $oldSlug],
                ['new_slug' => $model->getAttribute('slug')],
            );
        }
    }

    public function audit(string $action, Model $subject, array $properties = []): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'subject_type' => $subject->getMorphClass(),
            'subject_id' => $subject->getKey(),
            'properties' => Arr::except($properties, ['password']),
            'ip_address' => request()->ip(),
        ]);
    }
}
