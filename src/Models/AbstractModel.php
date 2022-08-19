<?php

declare(strict_types=1);

namespace Cortex\Foundation\Models;

use Rinvex\Tenants\Traits\Tenantable;
use Illuminate\Database\Eloquent\Model;

class AbstractModel extends Model
{
    use Tenantable;
}
