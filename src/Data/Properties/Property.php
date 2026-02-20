<?php

declare(strict_types=1);

namespace Uptick\PhpSdk\Uptick\Data\Properties;

final readonly class Property
{
    /**
     * @param  array<string, mixed>  $relationships
     */
    public function __construct(
        public string $id,
        public string $type,
        public PropertyAttributes $attributes,
        public array $relationships = [],
    ) {}

    /**
     * Create from JSON:API array response.
     */
    public static function fromArray(array $data): self
    {
        $attributes = isset($data['attributes']) && is_array($data['attributes'])
            ? PropertyAttributes::fromArray($data['attributes'])
            : PropertyAttributes::fromArray($data);

        return new self(
            id: (string) $data['id'],
            type: $data['type'] ?? 'Property',
            attributes: $attributes,
            relationships: $data['relationships'] ?? [],
        );
    }
}
