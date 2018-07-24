<?php

declare(strict_types=1);

namespace Cortex\Foundation\Tests\Feature;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
