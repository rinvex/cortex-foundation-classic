<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Yajra\DataTables\Html;

use Illuminate\Support\HtmlString;
use Yajra\DataTables\Html\Builder as BaseBuilder;

class Builder extends BaseBuilder
{
    /**
     * Array of pusher parameters.
     *
     * @var array
     */
    protected $pusher = [];

    /**
     * Generate DataTable's table html.
     *
     * @param array $attributes
     * @param bool  $drawFooter
     * @param bool  $drawSearch
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function table(array $attributes = [], $drawFooter = false, $drawSearch = false)
    {
        $this->tableAttributes = array_merge($this->getTableAttributes(), $attributes);
        $htmlAttr = $this->html->attributes($this->tableAttributes);
        $tableHtml = '<table '.$htmlAttr.'></table>';

        return new HtmlString($tableHtml);
    }

    /**
     * Get javascript template to use.
     *
     * @return string
     */
    protected function template()
    {
        $template = $this->template ?: $this->config->get('datatables-html.script', 'datatables::script');

        return $this->view->make($template, [
            'id' => $this->getTableAttribute('id'),
            'options' => $this->generateJson(),
            'editors' => $this->editors,
            'pusher' => $this->pusher,
        ])->render();
    }

    /**
     * Configure DataTable's pusher parameters.
     *
     * @param  array|null $pusher
     *
     * @return $this
     */
    public function pusher(array $pusher = null)
    {
        ! $pusher || $this->pusher = array_merge($this->pusher, $pusher);

        return $this;
    }
}
