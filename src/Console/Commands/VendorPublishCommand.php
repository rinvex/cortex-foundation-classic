<?php

declare(strict_types=1);

namespace Cortex\Foundation\Console\Commands;

use Illuminate\Console\ConfirmableTrait;
use Illuminate\Foundation\Console\VendorPublishCommand as BaseVendorPublishCommand;

class VendorPublishCommand extends BaseVendorPublishCommand
{
    use ConfirmableTrait;
}
