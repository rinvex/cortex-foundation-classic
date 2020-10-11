<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class ActivateCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cortex:activate {--f|force : Force the operation to run when in production.} {--m|module=* : Specify a module to activate.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate application modules';

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
     */
    protected function writeModulesManifest(bool $status)
    {
        $modules = $this->option('module');
        $path = $this->laravel->getCachedModulesPath();
        $statusStr = $status ? 'activate' : 'deactivate';

        $modulesManifest = collect($this->laravel['request.modules'])->map(function ($attributes, $module) use ($status, $modules) {
            switch ($module) {
                case 'cortex/auth':
                case 'cortex/foundation':
                    return ['active' => true, 'autoload' => true];
                    break;
                default:
                    return ! $modules || in_array($module, $modules) ? ['active' => $status, 'autoload' => $attributes['autoload']] : $attributes;
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

        $this->comment("Application modules {$statusStr}d!");

        return 0;
    }
}
