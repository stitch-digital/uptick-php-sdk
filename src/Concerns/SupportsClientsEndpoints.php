<?php

declare(strict_types=1);

namespace Uptick\PhpSdk\Uptick\Concerns;

use Uptick\PhpSdk\Uptick\Resources\ClientResource;

/**
 * @mixin \Uptick\PhpSdk\Uptick\Uptick
 */
trait SupportsClientsEndpoints
{
    public function clients(): ClientResource
    {
        return new ClientResource($this);
    }
}
