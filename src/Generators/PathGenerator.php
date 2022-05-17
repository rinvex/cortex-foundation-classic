<?php

declare(strict_types=1);

namespace Cortex\Foundation\Generators;

use Illuminate\Support\Str;
use Cortex\Foundation\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;

class PathGenerator extends DefaultPathGenerator
{
    protected function getBasePath(Media $media): string
    {
        return Str::plural(Str::slug($media->model->getMorphClass())).'/'.$media->collection_name.'/'.$media->getRouteKey();
    }
}
