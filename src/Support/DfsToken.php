<?php

declare(strict_types=1);

namespace Cortex\Foundation\Support;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class DfsToken
{
    /**
     * The current request object.
     *
     * @var \Illuminate\Http\Request
     */
    protected Request $request;

    /**
     * The DFS token fingerprint.
     *
     * @var string
     */
    protected string $fingerprint = '';

    /**
     * Create a new DfsToken instance.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get a unique fingerprint for the DFS token.
     *
     * @return string
     */
    public function fingerprint(): string
    {
        if (! $this->fingerprint && ($user = $this->request->user())) {
            $this->fingerprint = sha1(implode('|', [$user->getMorphClass(), $user['id'], $user['email'], $user['username']]));
        }

        return $this->fingerprint;
    }

    /**
     * Generate DFS token and put in cache.
     *
     * @return void
     */
    public function regenerateToken(): void
    {
        // @TODO: Add support for when caching is disabled too!
        cache()->put($this->fingerprint(), $this->generateTokenId());
    }

    /**
     * Get DFS token from cache.
     *
     * @return string
     */
    public function token(): string
    {
        if (! cache()->has($this->fingerprint())) {
            $this->regenerateToken();
        }

        return cache()->get($this->fingerprint());
    }

    /**
     * Generate DFS token Id.
     *
     * @return string
     */
    protected function generateTokenId()
    {
        return Str::random(40);
    }
}
