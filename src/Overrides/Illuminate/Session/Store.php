<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Illuminate\Session;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Session\Store as BaseStore;

class Store extends BaseStore
{
    /**
     * Start the session, reading the data from a handler.
     *
     * @return bool
     */
    public function start()
    {
        $this->loadSession();

        if (! $this->has('token')) {
            $this->regenerateToken();
        }

        return $this->started = true;
    }

    /**
     * Age the flash data for the session.
     *
     * @return void
     */
    public function ageFlashData()
    {
        $this->forget($this->get('flash.old', []));

        $this->put('flash.old', $this->get('flash.new', []));

        $this->put('flash.new', []);
    }

    /**
     * Flash a key / value pair to the session.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function flash(string $key, $value = true)
    {
        $this->put($key, $value);

        $this->push('flash.new', $key);

        $this->removeFromOldFlashData([$key]);
    }

    /**
     * Flash a key / value pair to the session for immediate use.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function now($key, $value)
    {
        $this->put($key, $value);

        $this->push('flash.old', $key);
    }

    /**
     * Reflash all of the session flash data.
     *
     * @return void
     */
    public function reflash()
    {
        $this->mergeNewFlashes($this->get('flash.old', []));

        $this->put('flash.old', []);
    }

    /**
     * Merge new flash keys into the new flash array.
     *
     * @param  array  $keys
     * @return void
     */
    protected function mergeNewFlashes(array $keys)
    {
        $values = array_unique(array_merge($this->get('flash.new', []), $keys));

        $this->put('flash.new', $values);
    }

    /**
     * Remove the given keys from the old flash data.
     *
     * @param  array  $keys
     * @return void
     */
    protected function removeFromOldFlashData(array $keys)
    {
        $this->put('flash.old', array_diff($this->get('flash.old', []), $keys));
    }

    /**
     * Flash an input array to the session.
     *
     * @param  array  $value
     * @return void
     */
    public function flashInput(array $value)
    {
        $this->flash('old_input', $value);
    }

    /**
     * Get the CSRF token value.
     *
     * @return string
     */
    public function token()
    {
        return $this->get('token');
    }

    /**
     * Regenerate the CSRF token value.
     *
     * @return void
     */
    public function regenerateToken()
    {
        $this->put('token', Str::random(40));
    }

    /**
     * Get the previous URL from the session.
     *
     * @return string|null
     */
    public function previousUrl()
    {
        return $this->get('previous.url');
    }

    /**
     * Set the "previous" URL in the session.
     *
     * @param  string  $url
     * @return void
     */
    public function setPreviousUrl($url)
    {
        $this->put('previous.url', $url);
    }

    /**
     * Get an item from the session.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return Arr::get($this->attributes, $this->getPrefixedKey($key), $default);
    }

    /**
     * Get the value of a given key and then forget it.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function pull($key, $default = null)
    {
        return Arr::pull($this->attributes, $this->getPrefixedKey($key), $default);
    }

    /**
     * Get the requested item from the flashed input array.
     *
     * @param string|null $key
     * @param mixed       $default
     *
     * @return mixed
     */
    public function getOldInput($key = null, $default = null)
    {
        return Arr::get($this->get('old_input', []), $this->getPrefixedKey($key), $default);
    }

    /**
     * Put a key / value pair or array of key / value pairs in the session.
     *
     * @param string|array $key
     * @param mixed        $value
     *
     * @return void
     */
    public function put($key, $value = null)
    {
        if (! is_array($key)) {
            $key = [$key => $value];
        }

        foreach ($key as $arrayKey => $arrayValue) {
            Arr::set($this->attributes, $this->getPrefixedKey($arrayKey), $arrayValue);
        }
    }

    /**
     * Remove an item from the session, returning its value.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function remove($key)
    {
        return Arr::pull($this->attributes, $this->getPrefixedKey($key));
    }

    /**
     * Remove one or many items from the session.
     *
     * @param string|array $keys
     *
     * @return void
     */
    public function forget($keys)
    {
        Arr::forget($this->attributes, $this->getPrefixedKeys($keys));
    }

    /**
     * Get a subset of the session data.
     *
     * @param array $keys
     *
     * @return array
     */
    public function only(array $keys)
    {
        return Arr::only($this->attributes, $this->getPrefixedKeys($keys));
    }

    /**
     * Get prefixed keys.
     *
     * @return array
     */
    public function getPrefixedKeys($keys)
    {
        return collect($keys)->map(fn ($key) => $this->getPrefixedKey($key))->toArray();
    }

    /**
     * Get prefixed key.
     *
     * @return string
     */
    public function getPrefixedKey($key)
    {
        return ! Str::contains($key, $this->getSha1()) ? $this->getPrefixedGuard().'.'.$key : $key;
    }

    /**
     * Get prefixed guard.
     *
     * @return string
     */
    public function getPrefixedGuard()
    {
        return request()->guard().'_'.$this->getSha1();
    }

    /**
     * Get the sha1 of static::class.
     *
     * @return string
     */
    public function getSha1()
    {
        return sha1(static::class);
    }
}
