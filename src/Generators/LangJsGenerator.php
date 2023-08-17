<?php

declare(strict_types=1);

namespace Cortex\Foundation\Generators;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Mariuzzo\LaravelJsLocalization\Generators\LangJsGenerator as BaseLangJsGenerator;

/**
 * The LangJsGenerator class.
 *
 * @author  Rubens Mariuzzo <rubens@mariuzzo.com>
 */
class LangJsGenerator extends BaseLangJsGenerator
{
    /**
     * Return all language messages.
     *
     * @param bool $noSort Whether sorting of the messages should be skipped.
     *
     * @throws \Exception
     *
     * @return array
     */
    protected function getMessages($noSort)
    {
        $messages = [];

        foreach (array_merge(array_values(app('translation.loader')->namespaces()), [$this->sourcePath]) as $directory) {
            foreach ($this->file->allFiles($directory) as $file) {
                $path = mb_substr($file->getPath(), 0, mb_strrpos($file->getPath(), DIRECTORY_SEPARATOR));
                $namespace = collect(app('translation.loader')->namespaces())->search(fn ($paths) => in_array($path, $paths));

                $pathName = $file->getRelativePathName();
                $extension = $file->getExtension();

                if (! in_array($extension, ['json', 'php'])) {
                    continue;
                }

                if ($this->isMessagesExcluded($pathName)) {
                    continue;
                }

                $key = mb_substr($pathName, 0, -4);
                $key = str_replace('\\', '.', $key);
                $key = str_replace('/', '.', $key);

                if ($namespace) {
                    $key = mb_substr($key, 0, mb_strpos($key, '.') + 1).str_replace('/', '.', $namespace).'::'.mb_substr($key, mb_strpos($key, '.') + 1);
                }

                if (Str::startsWith($key, 'vendor')) {
                    $key = $this->getVendorKey($key);
                }

                $fullPath = $file->getRealPath();

                if ($extension === 'php') {
                    $messages[$key] = include $fullPath;
                } else {
                    $key = $key.$this->stringsDomain;
                    $fileContent = file_get_contents($fullPath);
                    $messages[$key] = json_decode($fileContent, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new InvalidArgumentException('Error while decode '.basename($fullPath).': '.json_last_error_msg());
                    }
                }
            }
        }

        if (! $noSort) {
            $this->sortMessages($messages);
        }

        return $messages;
    }

    private function getVendorKey($key)
    {
        $keyParts = explode('.', $key, 4);
        unset($keyParts[0]);

        return $keyParts[2].'.'.$keyParts[1].'::'.$keyParts[3];
    }
}
