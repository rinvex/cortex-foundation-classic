<?php

declare(strict_types=1);

namespace Cortex\Foundation\Loaders;

use Illuminate\Translation\FileLoader as BaseFileLoader;

class FileLoader extends BaseFileLoader
{
    /**
     * Load a namespaced translation group.
     *
     * @param string $locale
     * @param string $group
     * @param string $namespace
     *
     * @return array
     */
    protected function loadNamespaced($locale, $group, $namespace)
    {
        if (isset($this->hints[$namespace])) {
            $lines = $this->loadPaths((array) $this->hints[$namespace], $locale, $group);

            return $this->loadNamespaceOverrides($lines, $locale, $group, $namespace);
        }

        return [];
    }

    /**
     * Add a new namespace to the loader.
     *
     * @param string       $namespace
     * @param string|array $hint
     *
     * @return void
     */
    public function addNamespace($namespace, $hint)
    {
        $hint = (array) $hint;

        if (isset($this->hints[$namespace])) {
            $hint = array_merge($this->hints[$namespace], $hint);
        }

        $this->hints[$namespace] = $hint;
    }
}
