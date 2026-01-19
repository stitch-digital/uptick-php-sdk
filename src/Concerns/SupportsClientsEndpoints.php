<?php

declare(strict_types=1);

namespace Uptick\PhpSdk\Uptick\Concerns;

use Uptick\PhpSdk\Uptick\Paginators\UptickPaginator;
use Uptick\PhpSdk\Uptick\Requests\Clients\ListClientsRequest;

/**
 * @mixin \Uptick\PhpSdk\Uptick\Uptick
 */
trait SupportsClientsEndpoints
{
    /**
     * List clients with pagination.
     */
    public function listClients(?int $page = null, ?int $perPage = null): UptickPaginator
    {
        $request = new ListClientsRequest($page, $perPage);

        return $this->paginate($request);
    }
}
