<?php

declare(strict_types=1);

namespace Uptick\PhpSdk\Uptick\Paginators;

use LogicException;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\OffsetPaginator;
use Uptick\PhpSdk\Uptick\Data\Clients\Client;

final class UptickPaginator extends OffsetPaginator
{
    /**
     * Default per page limit matching API default.
     */
    protected ?int $perPageLimit = 50;

    /**
     * Apply pagination to the request using Uptick's format.
     */
    protected function applyPagination(Request $request): Request
    {
        if (is_null($this->perPageLimit)) {
            throw new LogicException('Please define the $perPageLimit property on your paginator or use the setPerPageLimit method');
        }

        $request->query()->merge([
            'page[limit]' => $this->perPageLimit,
            'page[offset]' => $this->getOffset(),
        ]);

        return $request;
    }

    /**
     * Determine if we are on the last page.
     */
    protected function isLastPage(Response $response): bool
    {
        return is_null($response->json('links.next'));
    }

    /**
     * Get the page items from the response, casting each to a Client DTO.
     *
     * @return array<int, Client>
     */
    protected function getPageItems(Response $response, Request $request): array
    {
        $results = $response->json('results', []);

        return array_map(
            Client::fromArray(...),
            $results
        );
    }

    /**
     * Get the total number of pages.
     */
    protected function getTotalPages(Response $response): int
    {
        $count = $response->json('meta.pagination.count', 0);
        $limit = $response->json('meta.pagination.limit', $this->perPageLimit ?? 50);

        if ($limit === 0) {
            return 0;
        }

        return (int) ceil($count / $limit);
    }
}
