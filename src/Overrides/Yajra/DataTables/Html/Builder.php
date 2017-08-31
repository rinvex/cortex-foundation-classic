<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Yajra\DataTables\Html;

use Yajra\DataTables\Html\Builder as BaseBuilder;

class Builder extends BaseBuilder
{
    /**
     * Generate DataTable's table html.
     *
     * @param array $attributes
     * @param bool  $drawFooter
     *
     * @return string
     */
    public function table(array $attributes = [], $drawFooter = false)
    {
        $this->tableAttributes = array_merge($this->tableAttributes, $attributes);
        $htmlAttr = $this->html->attributes($this->tableAttributes);
        $tableHtml = '<table '.$htmlAttr.'></table>';

        return $tableHtml;
    }
}
