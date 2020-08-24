<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class AutoloadCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cortex:autoload {--f|force : Force the operation to run when in production.} {--m|module=* : Specify a module to activate.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Autoload application modules';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        return $this->writeModulesManifest(true);
    }

    /**
     * Write the given manifest array to disk.
     *
     * @param bool $status
     *
     * @return int
     */
    protected function writeModulesManifest(bool $status): int
    {
        $this->call('clear-compiled');

        $modules = $this->option('module');
        $path = $this->laravel->getCachedModulesPath();
        $statusStr = $status ? 'autoload' : 'unload';

        $modulesManifest = collect($this->laravel['request.modules'])->map(function ($attributes, $module) use ($modules, $status) {
            switch ($module) {
                case 'cortex/auth':
                case 'cortex/foundation':
                    return ['active' => true, 'autoload' => true];
                    break;
                default:
                    return ! $modules || in_array($module, $modules) ? ['active' => $attributes['active'], 'autoload' => $status ? true : false] : $attributes;
                    break;
            }
        })->toArray();

        if (! is_writable($dirname = dirname($path))) {
            $this->error("Failed to {$statusStr} application modules.");

            $this->error("The {$dirname} directory must be present and writable.");

            return 1;
        }

        $this->laravel['files']->replace(
            $path, '<?php return '.var_export($modulesManifest, true).';'
        );

        $this->comment("Application modules {$statusStr}ed!");

        return 0;
    }
}
