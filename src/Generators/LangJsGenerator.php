<?php

declare(strict_types=1);

namespace Cortex\Foundation\Generators;

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
     * @return array
     *
     * @throws \Exception
     */
    protected function getMessages()
    {
        $messages = [];

        foreach (array_merge(array_values(app('translation.loader')->namespaces()), [$this->sourcePath]) as $directory) {
            foreach ($this->file->allFiles($directory) as $file) {
                $path = substr($file->getPath(), 0, strrpos($file->getPath(), DIRECTORY_SEPARATOR));
                $namespace = array_search($path, app('translation.loader')->namespaces());

                $pathName = $file->getRelativePathName();
                $extension = $file->getExtension();
                if (! in_array($extension, ['json', 'php'])) {
                    continue;
                }

                if ($this->isMessagesExcluded($pathName)) {
                    continue;
                }

                $key = substr($pathName, 0, -4);
                $key = str_replace('\\', '.', $key);
                $key = str_replace('/', '.', $key);

                if ($namespace) {
                    $key = substr($key, 0, strpos($key, '.')+1).str_replace('/', '.', $namespace).'::'.substr($key, strpos($key, '.')+1);
                }

                if (starts_with($key, 'vendor')) {
                    $key = $this->getVendorKey($key);
                }

                if ($extension == 'php') {
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

        return $keyParts[2] .'.'. $keyParts[1] . '::' . $keyParts[3];
    }
}
