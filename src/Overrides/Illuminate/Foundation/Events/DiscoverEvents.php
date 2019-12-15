<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Illuminate\Foundation\Events;

use SplFileInfo;
use Illuminate\Support\Str;
use Illuminate\Foundation\Events\DiscoverEvents as BaseDiscoverEvents;

class DiscoverEvents extends BaseDiscoverEvents
{
    /**
     * Extract the class name from the given file path.
     *
     * @param \SplFileInfo $file
     * @param string       $basePath
     *
     * @return string
     */
    protected static function classFromFile(SplFileInfo $file, $basePath)
    {
        $class = trim(Str::replaceFirst(app()->basePath('app'), '', $file->getRealPath()), DIRECTORY_SEPARATOR);

        return str_replace(
            [DIRECTORY_SEPARATOR, '\Src'],
            ['\\', ''],
            ucwords(Str::replaceLast('.php', '', $class), " \t\r\n\f\v/")
        );
    }
}
