<?php

declare(strict_types=1);

namespace Cortex\Foundation\Generators;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\PathGenerator\BasePathGenerator;

class PathGenerator extends BasePathGenerator
{
    protected function getBasePath(Media $media): string
    {
        return Str::plural(Str::slug($media->model->getMorphClass())).'/'.$media->collection_name.'/'.$media->getRouteKey();
    }
}
