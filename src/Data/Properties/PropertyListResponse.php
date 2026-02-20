<?php

declare(strict_types=1);

namespace Uptick\PhpSdk\Uptick\Data\Properties;

use Uptick\PhpSdk\Uptick\Data\PaginationLinks;
use Uptick\PhpSdk\Uptick\Data\PaginationMeta;

final readonly class PropertyListResponse
{
    /**
     * @param  array<int, Property>  $properties
     */
    public function __construct(
        public array $properties,
        public ?PaginationLinks $links = null,
        public ?PaginationMeta $meta = null,
    ) {}

    /**
     * Create from array response.
     */
    public static function fromArray(array $data): self
    {
        $properties = array_map(
            Property::fromArray(...),
            $data['results'] ?? []
        );

        $links = isset($data['links']) ? PaginationLinks::fromArray($data['links']) : null;
        $meta = isset($data['meta']) ? PaginationMeta::fromArray($data['meta']) : null;

        return new self($properties, $links, $meta);
    }
}
