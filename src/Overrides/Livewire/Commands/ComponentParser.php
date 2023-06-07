<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Livewire\Commands;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Livewire\Commands\ComponentParser as BaseComponentParser;

use function Livewire\str;

class ComponentParser extends BaseComponentParser
{
    protected $accessarea;

    public function __construct($classNamespace, $classPath, $viewPath, $accessarea, $rawCommand, $stubSubDirectory = '')
    {
        $this->accessarea = $accessarea;
        $this->baseClassNamespace = $classNamespace;
        $this->baseTestNamespace = 'Tests\Feature\Livewire';

        $testPath = static::generateTestPathFromNamespace($this->baseTestNamespace)->__toString();

        $this->baseClassPath = rtrim($classPath, DIRECTORY_SEPARATOR).'/'.ucfirst($this->accessarea).'/';
        $this->baseViewPath = rtrim($viewPath, DIRECTORY_SEPARATOR).'/'.$this->accessarea.'/components/';
        $this->baseTestPath = rtrim($testPath, DIRECTORY_SEPARATOR).'/';

        if (! empty($stubSubDirectory) && str($stubSubDirectory)->startsWith('..')) {
            $this->stubDirectory = rtrim(str($stubSubDirectory)->replaceFirst('..'.DIRECTORY_SEPARATOR, ''), DIRECTORY_SEPARATOR).'/';
        } else {
            $this->stubDirectory = rtrim('stubs'.DIRECTORY_SEPARATOR.$stubSubDirectory, DIRECTORY_SEPARATOR).'/';
        }

        $directories = preg_split('/[.\/(\\\\)]+/', $rawCommand);

        $camelCase = str(array_pop($directories))->camel();
        $kebabCase = str($camelCase)->kebab();

        $this->component = $kebabCase;
        $this->componentClass = str($this->component)->studly()->__toString();

        $this->directories = array_map([Str::class, 'studly'], $directories);
    }

    /**
     * Get the destination module name.
     *
     * @return string
     */
    protected function moduleName(): string
    {
        return mb_strtolower(trim(str_replace('\\', '/', $this->baseClassNamespace), " \t\n\r\0\x0B\\/"));
    }

    public function viewName()
    {
        return $this->moduleName().'::'.$this->accessarea.'.livewire.'.$this->component;
    }

    public function classNamespace()
    {
        return empty($this->directories)
            ? $this->baseClassNamespace.'Http\\Components\\'.ucfirst($this->accessarea)
            : $this->baseClassNamespace.'Http\\Components\\'.ucfirst($this->accessarea).'\\'.collect()
                ->concat($this->directories)
                ->map([Str::class, 'studly'])
                ->implode('\\');
    }

    public function classContents($inline = false)
    {
        $stubName = $inline ? 'livewire.inline.stub' : 'livewire.stub';

        if (File::exists($stubPath = base_path($this->stubDirectory.$stubName))) {
            $template = file_get_contents($stubPath);
        } else {
            $template = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.$stubName);
        }

        if ($inline) {
            $template = preg_replace('/\[quote\]/', $this->wisdomOfTheTao(), $template);
        }

        return preg_replace(['/\[namespace\]/', '/\[class\]/', '/\[view\]/'], [
            $this->classNamespace(),
            $this->className(),
            $this->viewName(),
        ], $template);
    }

    public function viewContents()
    {
        if (! File::exists($stubPath = base_path($this->stubDirectory.'livewire.view.stub'))) {
            $stubPath = __DIR__.DIRECTORY_SEPARATOR.'livewire.view.stub';
        }

        return preg_replace('/\[quote\]/', $this->wisdomOfTheTao(), file_get_contents($stubPath));
    }

    public function testContents()
    {
        $stubName = 'livewire.test.stub';

        if (File::exists($stubPath = base_path($this->stubDirectory.$stubName))) {
            $template = file_get_contents($stubPath);
        } else {
            $template = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.$stubName);
        }

        return preg_replace(
            ['/\[testnamespace\]/', '/\[classwithnamespace\]/', '/\[testclass\]/', '/\[class\]/'],
            [$this->testNamespace(), $this->classNamespace().'\\'.$this->className(), $this->testClassName(), $this->className()],
            $template
        );
    }
}
