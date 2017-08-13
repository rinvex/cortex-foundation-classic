<?php

declare(strict_types=1);

namespace Cortex\Foundation\Models;

use Spatie\Menu\Item;
use Spatie\Menu\Html\Tag;
use Illuminate\Support\Arr;
use Spatie\Menu\Laravel\View;
use Spatie\Menu\Laravel\Html;
use Spatie\Menu\Laravel\Link;
use Spatie\Menu\Html\Attributes;
use Spatie\Menu\Helpers\Reflection;
use Spatie\Menu\Laravel\Menu as BaseMenu;
use Illuminate\Contracts\Auth\Access\Gate;

class Menu extends BaseMenu
{
    /** @var string */
    protected $section;

    /** @var array */
    protected $sections = [];

    /**
     * Set current menu section.
     *
     * @param string $section
     *
     * @return $this
     */
    public function setSection($section)
    {
        $this->section = $section;

        if ($section && ! in_array($section, $this->sections)) {
            $this->sections[] = $section;
            $this->add(Html::raw(trans('cortex/foundation::menus.'.$section))->addParentClass('header'));
        }

        return $this;
    }

    /**
     * Add an item to the menu. This also applies all registered filters to the
     * item.
     *
     * @param \Spatie\Menu\Item $item
     *
     * @return $this
     */
    public function add(Item $item)
    {
        foreach ($this->filters as $filter) {
            $this->applyFilter($filter, $item);
        }

        if (func_num_args() === 2 && $extra = func_get_arg(1)) {
            if ($this->section) {
                $this->items[$this->section][func_get_arg(1)] = $item;
            } else {
                $this->items[func_get_arg(1)] = $item;
            }
        } else {
            if ($this->section) {
                $this->items[$this->section][] = $item;
            } else {
                $this->items[] = $item;
            }
        }

        return $this;
    }

    /**
     * Add an item to the menu if a (non-strict) condition is met.
     *
     * @param bool              $condition
     * @param \Spatie\Menu\Item $item
     *
     * @return $this
     */
    public function addIf($condition, Item $item)
    {
        if ($this->resolveCondition($condition)) {
            $this->add($item, func_num_args() === 3 ? func_get_arg(2) : null);
        }

        return $this;
    }

    /**
     * Shortcut function to add a plain link to the menu.
     *
     * @param string $url
     * @param string $text
     *
     * @return $this
     */
    public function link(string $url, string $text)
    {
        return $this->add(Link::to($url, $text), func_num_args() === 3 ? func_get_arg(2) : null);
    }

    /**
     * Add a link to the menu if a (non-strict) condition is met.
     *
     * @param bool   $condition
     * @param string $url
     * @param string $text
     *
     * @return $this
     */
    public function linkIf($condition, string $url, string $text)
    {
        if ($this->resolveCondition($condition)) {
            $this->link($url, $text, func_num_args() === 4 ? func_get_arg(3) : null);
        }

        return $this;
    }

    /**
     * Shortcut function to add raw html to the menu.
     *
     * @param string $html
     * @param array  $parentAttributes
     *
     * @return $this
     */
    public function html(string $html, array $parentAttributes = [])
    {
        return $this->add(Html::raw($html)->setParentAttributes($parentAttributes), func_num_args() === 3 ? func_get_arg(2) : null);
    }

    /**
     * Add a chunk of html if a (non-strict) condition is met.
     *
     * @param bool   $condition
     * @param string $html
     * @param array  $parentAttributes
     *
     * @return $this
     */
    public function htmlIf($condition, string $html, array $parentAttributes = [])
    {
        if ($this->resolveCondition($condition)) {
            $this->html($html, $parentAttributes, func_num_args() === 4 ? func_get_arg(3) : null);
        }

        return $this;
    }

    /**
     * @param callable|\Spatie\Menu\Menu|\Spatie\Menu\Item $header
     * @param callable|\Spatie\Menu\Menu|null              $menu
     *
     * @return $this
     */
    public function submenu($header, $menu = null)
    {
        list($header, $menu) = $this->parseSubmenuArgs(func_get_args());

        $menu = $this->createSubmenuMenu($menu);
        $header = $this->createSubmenuHeader($header);

        return $this->add($menu->prependIf($header, $header), func_num_args() === 3 ? func_get_arg(2) : null);
    }

    /**
     * @param bool                                         $condition
     * @param callable|\Spatie\Menu\Menu|\Spatie\Menu\Item $header
     * @param callable|\Spatie\Menu\Menu|null              $menu
     *
     * @return $this
     */
    public function submenuIf($condition, $header, $menu = null)
    {
        if ($condition) {
            $this->submenu($header, $menu, func_num_args() === 4 ? func_get_arg(3) : null);
        }

        return $this;
    }

    /**
     * @param string    $path
     * @param string    $text
     * @param mixed     $parameters
     * @param bool|null $secure
     *
     * @return $this
     */
    public function url(string $path, string $text, $parameters = [], $secure = null)
    {
        return $this->add(Link::toUrl($path, $text, $parameters, $secure), func_num_args() === 5 ? func_get_arg(4) : null);
    }

    /**
     * @param string $action
     * @param string $text
     * @param mixed  $parameters
     * @param bool   $absolute
     *
     * @return $this
     */
    public function action(string $action, string $text, $parameters = [], bool $absolute = true)
    {
        return $this->add(Link::toAction($action, $text, $parameters, $absolute), func_num_args() === 5 ? func_get_arg(4) : null);
    }

    /**
     * @param string $name
     * @param string $text
     * @param mixed  $parameters
     * @param bool   $absolute
     *
     * @return $this
     */
    public function route(string $name, string $text, $parameters = [], bool $absolute = true)
    {
        return $this->add(Link::toRoute($name, $text, $parameters, $absolute), func_num_args() === 5 ? func_get_arg(4) : null);
    }

    /**
     * @param string $name
     * @param array  $data
     *
     * @return $this
     */
    public function view(string $name, array $data = [])
    {
        return $this->add(View::create($name, $data), func_num_args() === 3 ? func_get_arg(2) : null);
    }

    /**
     * @param bool      $condition
     * @param string    $path
     * @param string    $text
     * @param array     $parameters
     * @param bool|null $secure
     *
     * @return $this
     */
    public function urlIf($condition, string $path, string $text, array $parameters = [], $secure = null)
    {
        return $this->addIf($condition, Link::toUrl($path, $text, $parameters, $secure), func_num_args() === 6 ? func_get_arg(5) : null);
    }

    /**
     * @param bool   $condition
     * @param string $action
     * @param string $text
     * @param array  $parameters
     * @param bool   $absolute
     *
     * @return $this
     */
    public function actionIf($condition, string $action, string $text, array $parameters = [], bool $absolute = true)
    {
        return $this->addIf($condition, Link::toAction($action, $text, $parameters, $absolute), func_num_args() === 6 ? func_get_arg(5) : null);
    }

    /**
     * @param bool                           $condition
     * @param string                         $name
     * @param string                         $text
     * @param array                          $parameters
     * @param bool                           $absolute
     * @param \Illuminate\Routing\Route|null $route
     *
     * @return $this
     */
    public function routeIf($condition, string $name, string $text, array $parameters = [], bool $absolute = true, $route = null)
    {
        return $this->addIf($condition, Link::toRoute($name, $text, $parameters, $absolute, $route), func_num_args() === 7 ? func_get_arg(6) : null);
    }

    /**
     * @param        $condition
     * @param string $name
     * @param array  $data
     *
     * @return $this
     */
    public function viewIf($condition, string $name, array $data = null)
    {
        return $this->addIf($condition, View::create($name, $data), func_num_args() === 4 ? func_get_arg(3) : null);
    }

    /**
     * @param string|array      $authorization
     * @param \Spatie\Menu\Item $item
     *
     * @return $this
     */
    public function addIfCan($authorization, Item $item)
    {
        $ablityArguments = is_array($authorization) ? $authorization : [$authorization];
        $ability = array_shift($ablityArguments);

        return $this->addIf(app(Gate::class)->allows($ability, $ablityArguments), $item, func_num_args() === 3 ? func_get_arg(2) : null);
    }

    /**
     * @param string|array $authorization
     * @param string       $url
     * @param string       $text
     *
     * @return $this
     */
    public function linkIfCan($authorization, string $url, string $text)
    {
        return $this->addIfCan($authorization, Link::to($url, $text), func_num_args() === 4 ? func_get_arg(3) : null);
    }

    /**
     * @param string|array $authorization
     * @param string       $html
     *
     * @return \Spatie\Menu\Laravel\Menu
     */
    public function htmlIfCan($authorization, string $html)
    {
        return $this->addIfCan($authorization, Html::raw($html), func_num_args() === 3 ? func_get_arg(2) : null);
    }

    /**
     * @param string|array                                 $authorization
     * @param callable|\Spatie\Menu\Menu|\Spatie\Menu\Item $header
     * @param callable|\Spatie\Menu\Menu|null              $menu
     *
     * @return $this
     */
    public function submenuIfCan($authorization, $header, $menu = null)
    {
        list($authorization, $header, $menu) = $this->parseSubmenuIfCanArgs(...func_get_args());

        $menu = $this->createSubmenuMenu($menu);
        $header = $this->createSubmenuHeader($header);

        return $this->addIfCan($authorization, $menu->prependIf($header, $header), func_num_args() === 4 ? func_get_arg(3) : null);
    }

    /**
     * @param string|array $authorization
     * @param string       $path
     * @param string       $text
     * @param array        $parameters
     * @param bool|null    $secure
     *
     * @return $this
     */
    public function urlIfCan($authorization, string $path, string $text, array $parameters = [], $secure = null)
    {
        return $this->addIfCan($authorization, Link::toUrl($path, $text, $parameters, $secure), func_num_args() === 6 ? func_get_arg(5) : null);
    }

    /**
     * @param string|array $authorization
     * @param string       $action
     * @param string       $text
     * @param array        $parameters
     * @param bool         $absolute
     *
     * @return $this
     */
    public function actionIfCan($authorization, string $action, string $text, array $parameters = [], bool $absolute = true)
    {
        return $this->addIfCan($authorization, Link::toAction($action, $text, $parameters, $absolute), func_num_args() === 6 ? func_get_arg(5) : null);
    }

    /**
     * @param string|array                   $authorization
     * @param string                         $name
     * @param string                         $text
     * @param array                          $parameters
     * @param bool                           $absolute
     * @param \Illuminate\Routing\Route|null $route
     *
     * @return $this
     */
    public function routeIfCan($authorization, string $name, string $text, array $parameters = [], bool $absolute = true, $route = null)
    {
        return $this->addIfCan($authorization, Link::toRoute($name, $text, $parameters, $absolute, $route), func_num_args() === 7 ? func_get_arg(6) : null);
    }

    /**
     * Iterate over all the items and apply a callback. If you typehint the
     * item parameter in the callable, it wil only be applied to items of that
     * type.
     *
     * @param callable $callable
     *
     * @return $this
     */
    public function each(callable $callable)
    {
        $items = Arr::isAssoc($this->items) ? array_collapse($this->items) : $this->items;
        $type = Reflection::firstParameterType($callable);

        foreach ($items as $item) {
            if (! Reflection::itemMatchesType($item, $type)) {
                continue;
            }

            $callable($item);
        }

        return $this;
    }

    /**
     * Determine whether the menu is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        $items = Arr::isAssoc($this->items) ? array_collapse($this->items) : $this->items;

        foreach ($items as $item) {
            if ($item->isActive()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param        $condition
     * @param string $name
     * @param array  $data
     *
     * @return $this
     */
    public function viewIfCan($authorization, string $name, array $data = null)
    {
        return $this->addIfCan($authorization, View::create($name, $data), func_num_args() === 4 ? func_get_arg(3) : null);
    }

    /**
     * Render the menu.
     *
     * @return string
     */
    public function render(): string
    {
        $items = Arr::isAssoc($this->items) ? array_collapse($this->items) : $this->items;

        $this->ksortRecursive($items);
        $tag = new Tag('ul', $this->htmlAttributes);

        $contents = array_map([$this, 'renderItem'], $items);

        $menu = $this->prepend.$tag->withContents($contents).$this->append;

        if (! empty($this->wrap)) {
            return Tag::make($this->wrap[0], new Attributes($this->wrap[1]))->withContents($menu);
        }

        return $menu;
    }

    /**
     * The amount of items in the menu.
     *
     * @return int
     */
    public function count(): int
    {
        return count(array_collapse($this->items));
    }

    /**
     * Sort array by keys recursively.
     *
     * @param     $array
     * @param int $sort_flags
     *
     * @return bool
     */
    protected function ksortRecursive(&$array, $sort_flags = SORT_REGULAR)
    {
        if (! is_array($array)) {
            return false;
        }

        ksort($array, $sort_flags);

        foreach ($array as &$arr) {
            $this->ksortRecursive($arr, $sort_flags);
        }

        return true;
    }
}
