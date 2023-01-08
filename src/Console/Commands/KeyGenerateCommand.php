<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Encryption\Encrypter;
use Symfony\Component\Console\Attribute\AsCommand;
use Illuminate\Foundation\Console\KeyGenerateCommand as BaseKeyGenerateCommand;

#[AsCommand(name: 'key:generate')]
class KeyGenerateCommand extends BaseKeyGenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'key:generate
                    {--ifnot : Persist the key only if none exists}
                    {--show : Display the key instead of modifying files}
                    {--force : Force the operation to run when in production}';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $key = $this->generateRandomKey();

        if ($this->option('show')) {
            return $this->line('<comment>'.$key.'</comment>');
        }

        if ($this->option('ifnot') && Encrypter::supported($this->parseKey(config('app.key')), config('app.cipher'))) {
            return $this->line('<comment>There is a key already exists, no changes has been made!</comment>');
        }

        // Next, we will replace the application key in the environment file so it is
        // automatically setup for this developer. This key gets generated using a
        // secure random byte generator and is later base64 encoded for storage.
        if (! $this->setKeyInEnvironmentFile($key)) {
            return;
        }

        $this->laravel['config']['app.key'] = $key;

        $this->info('Application key set successfully.');
    }

    /**
     * Parse the encryption key.
     *
     * @param string $key
     *
     * @return string
     */
    protected function parseKey(string $key)
    {
        if (Str::startsWith($key, $prefix = 'base64:')) {
            $key = base64_decode(Str::after($key, $prefix));
        }

        return $key;
    }
}
