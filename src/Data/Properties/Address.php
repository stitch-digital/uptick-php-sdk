<?php

declare(strict_types=1);

namespace Uptick\PhpSdk\Uptick\Data\Properties;

final readonly class Address
{
    public function __construct(
        public ?string $country = null,
        public ?string $display = null,
        public ?string $streetline = null,
        public ?string $city = null,
        public ?string $state = null,
        public ?string $countryName = null,
        public ?string $postalCode = null,
        public ?string $placeId = null,
    ) {}

    /**
     * Create from array response.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            country: $data['country'] ?? null,
            display: $data['display'] ?? null,
            streetline: $data['streetline'] ?? null,
            city: $data['city'] ?? null,
            state: $data['state'] ?? null,
            countryName: $data['country_name'] ?? null,
            postalCode: $data['postal_code'] ?? null,
            placeId: $data['place_id'] ?? null,
        );
    }
}
