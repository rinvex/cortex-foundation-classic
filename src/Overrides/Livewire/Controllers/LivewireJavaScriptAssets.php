<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Livewire\Controllers;

class LivewireJavaScriptAssets
{
    use CanPretendToBeAFile;

    public function turbo()
    {
        return $this->pretendResponseIsFile(app_path('cortex/foundation/resources/js/livewire/turbo.js'));
    }

    public function source()
    {
        return $this->pretendResponseIsFile(app_path('cortex/foundation/resources/js/livewire/livewire.js'));
    }

    public function maps()
    {
        return $this->pretendResponseIsFile(app_path('cortex/foundation/resources/js/livewire/livewire.js.map'));
    }
}
