<?php

declare(strict_types=1);

namespace Cortex\Foundation\Generators;

use Illuminate\Support\Str;
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
     * @return array
     *
     * @throws \Exception
     */
    protected function getMessages($noSort)
    {
        $messages = [];

        foreach (array_merge(array_values(app('translation.loader')->namespaces()), [$this->sourcePath]) as $directory) {
            foreach ($this->file->allFiles($directory) as $file) {
                $path = mb_substr($file->getPath(), 0, mb_strrpos($file->getPath(), DIRECTORY_SEPARATOR));
                $namespace = array_search($path, app('translation.loader')->namespaces());

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

                if ($extension === 'php') {
                    $messages[$key] = include $file->getRealPath();
                } else {
                    $key = $key.$this->stringsDomain;
                    $fileContent = file_get_contents($file->getRealPath());
                    $messages[$key] = json_decode($fileContent, true);
                }
            }
        }

        $this->sortMessages($messages);

        return $messages;
    }

    private function getVendorKey($key)
    {
        $keyParts = explode('.', $key, 4);
        unset($keyParts[0]);

        return $keyParts[2].'.'.$keyParts[1].'::'.$keyParts[3];
    }
}
