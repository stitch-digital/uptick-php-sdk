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
     * Create from array response.
     */
    public static function fromArray(array $data): self
    {
        // Extract relationships from flattened structure
        $relationships = [];
        $relationshipFields = ['client', 'account_manager', 'billingcard', 'parent_property', 'branch', 'pricetier', 'zone', 'iotsite'];

        foreach ($relationshipFields as $field) {
            if (isset($data[$field])) {
                $relationships[$field] = $data[$field];
            }
        }

        return new self(
            id: (string) $data['id'],
            type: 'Property',
            attributes: PropertyAttributes::fromArray($data),
            relationships: $relationships,
        );
    }
}
