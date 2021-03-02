<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Illuminate\Foundation\Console\StubPublishCommand as BaseStubPublishCommand;

class StubPublishCommand extends BaseStubPublishCommand
{
    use ConfirmableTrait;
}
