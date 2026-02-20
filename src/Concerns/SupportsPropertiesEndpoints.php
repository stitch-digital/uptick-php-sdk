<?php

declare(strict_types=1);

namespace Uptick\PhpSdk\Uptick\Concerns;

use Uptick\PhpSdk\Uptick\Resources\PropertyResource;

/**
 * @mixin \Uptick\PhpSdk\Uptick\Uptick
 */
trait SupportsPropertiesEndpoints
{
    public function properties(): PropertyResource
    {
        return new PropertyResource($this);
    }
}
