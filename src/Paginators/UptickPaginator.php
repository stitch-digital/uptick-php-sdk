<?php

declare(strict_types=1);

namespace Uptick\PhpSdk\Uptick\Paginators;

use LogicException;
use ReflectionClass;
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
     * Get the page items from the response, extracting them from the request's DTO if available.
     *
     * @return array<int, mixed>
     */
    protected function getPageItems(Response $response, Request $request): array
    {
        // Try to use the request's DTO method to extract items
        try {
            $dto = $request->createDtoFromResponse($response);

            // Extract items from the DTO by checking common property names
            $items = $this->extractItemsFromDto($dto);

            if ($items !== null) {
                return $items;
            }
        } catch (\Throwable) {
            // If DTO extraction fails, fall through to legacy behavior
        }

        // Fallback to legacy behavior for backward compatibility
        $results = $response->json('results', []);

        return array_map(
            Client::fromArray(...),
            $results
        );
    }

    /**
     * Extract items array from a DTO by checking common property names.
     *
     * @return array<int, mixed>|null
     */
    private function extractItemsFromDto(mixed $dto): ?array
    {
        if (! is_object($dto)) {
            return null;
        }

        // Common property names that might contain the items array
        $propertyNames = ['clients', 'items', 'results', 'data'];

        foreach ($propertyNames as $propertyName) {
            if (property_exists($dto, $propertyName)) {
                // Use reflection to safely access the property
                $reflection = new ReflectionClass($dto);
                if ($reflection->hasProperty($propertyName)) {
                    $property = $reflection->getProperty($propertyName);
                    if ($property->isPublic()) {
                        $value = $property->getValue($dto);

                        if (is_array($value)) {
                            return $value;
                        }
                    }
                }
            }
        }

        return null;
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
