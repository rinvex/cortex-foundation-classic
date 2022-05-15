<?php

declare(strict_types=1);

namespace Cortex\Foundation\Models;

use Rinvex\Support\Traits\HashidsTrait;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    use HashidsTrait;
}
