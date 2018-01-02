<?php

declare(strict_types=1);

namespace Cortex\Foundation\Generators;

use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\PathGenerator\BasePathGenerator;

class PathGenerator extends BasePathGenerator
{
    protected function getBasePath(Media $media): string
    {
        return str_plural(str_slug($media->model->getMorphClass())).'/'.str_plural($media->getTypeAttribute()).'/'.$media->getKey();
    }
}
