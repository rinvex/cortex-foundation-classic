<?php

namespace Cortex\Foundation\View\Components;

use Cortex\Foundation\Models\AdjustableLayout;
use Illuminate\Support\Arr;
use Illuminate\View\Component;

class DashboardTile extends Component
{
    public string $name;

    public string $title;

    public null|bool $resizable;

    public null|bool $sortable;

    public null|bool $is_enable;

    public int $position;

    public int $width;

    public int $height;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $id,
        string $title,
        ?bool $sortable = true,
        ?bool $resizable = false,
        ?bool $is_enable = true
    )
    {
        $this->name = $id;
        $this->title = $title;
        $this->resizable = $resizable;
        $this->sortable = $sortable;
        $this->is_enable = $is_enable;
        $this->position = 1000;
        $this->width = 350;
        $this->height = 350;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $this->adjustSettings();
        return view('cortex/foundation::components.dashboard-tile');
    }

    private function adjustSettings()
    {
        $setting = AdjustableLayout::query()->where('element_id', $this->name)->first();
        if ($setting) {
            $this->position = Arr::get($setting->data, 'position', 1000);
            $this->width = Arr::get($setting->data, 'width', 350);
            $this->height = Arr::get($setting->data, 'height', 350);
        }
    }

}
