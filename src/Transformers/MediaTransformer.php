<?php

declare(strict_types=1);

namespace Cortex\Foundation\Transformers;

use Rinvex\Support\Traits\Escaper;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use League\Fractal\TransformerAbstract;

class MediaTransformer extends TransformerAbstract
{
    use Escaper;

    /**
     * @return array
     */
    public function transform(Media $media): array
    {
        return $this->escape([
            'id' => (int) $media->getKey(),
            'name' => (string) $media->name,
            'file_name' => (string) $media->file_name,
            'mime_type' => (string) $media->mime_type,
            'size' => (string) $media->getHumanReadableSizeAttribute(),
            'created_at' => (string) $media->created_at,
            'updated_at' => (string) $media->updated_at,
            'delete' => (string) route('adminarea.rooms.media.destroy', ['room' => $media->model, 'media' => $media]),
        ]);
    }
}
