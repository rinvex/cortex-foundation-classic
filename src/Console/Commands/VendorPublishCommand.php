<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Illuminate\Foundation\Console\VendorPublishCommand as BaseVendorPublishCommand;

#[AsCommand(name: 'vendor:publish')]
class VendorPublishCommand extends BaseVendorPublishCommand
{
    use ConfirmableTrait;
}
