<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Yajra\DataTables\Html;

use Illuminate\Support\HtmlString;
use Yajra\DataTables\Html\Builder as BaseBuilder;

class Builder extends BaseBuilder
{
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
}
