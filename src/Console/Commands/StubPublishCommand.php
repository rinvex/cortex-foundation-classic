<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Illuminate\Foundation\Console\StubPublishCommand as BaseStubPublishCommand;

#[AsCommand(name: 'stub:publish')]
class StubPublishCommand extends BaseStubPublishCommand
{
    use ConfirmableTrait;
}
