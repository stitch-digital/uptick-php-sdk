<?php

declare(strict_types=1);

namespace Uptick\PhpSdk\Uptick\Data\Properties;

final readonly class Coordinates
{
    public function __construct(
        public ?float $lat = null,
        public ?float $lng = null,
    ) {}

    /**
     * Create from array response.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            lat: isset($data['lat']) && is_numeric($data['lat']) ? (float) $data['lat'] : null,
            lng: isset($data['lng']) && is_numeric($data['lng']) ? (float) $data['lng'] : null,
        );
    }
}
