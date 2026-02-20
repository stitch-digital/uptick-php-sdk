<?php

declare(strict_types=1);

use Uptick\PhpSdk\Uptick\Data\Properties\Address;

it('creates address from array', function () {
    $data = [
        'country' => 'AU',
        'display' => '123 Test Street, Sydney NSW 2000',
        'streetline' => '123 Test Street',
        'city' => 'Sydney',
        'state' => 'NSW',
        'country_name' => 'Australia',
        'postal_code' => '2000',
        'place_id' => 'ChIJP3Sa8ziYEmsRUKgyFmh9AQM',
    ];

    $address = Address::fromArray($data);

    expect($address->country)->toBe('AU')
        ->and($address->display)->toBe('123 Test Street, Sydney NSW 2000')
        ->and($address->streetline)->toBe('123 Test Street')
        ->and($address->city)->toBe('Sydney')
        ->and($address->state)->toBe('NSW')
        ->and($address->countryName)->toBe('Australia')
        ->and($address->postalCode)->toBe('2000')
        ->and($address->placeId)->toBe('ChIJP3Sa8ziYEmsRUKgyFmh9AQM');
});

it('handles missing fields gracefully', function () {
    $address = Address::fromArray([]);

    expect($address->country)->toBeNull()
        ->and($address->display)->toBeNull()
        ->and($address->streetline)->toBeNull()
        ->and($address->city)->toBeNull()
        ->and($address->state)->toBeNull()
        ->and($address->countryName)->toBeNull()
        ->and($address->postalCode)->toBeNull()
        ->and($address->placeId)->toBeNull();
});

it('handles partial data', function () {
    $data = [
        'country' => 'GB',
        'display' => '-',
    ];

    $address = Address::fromArray($data);

    expect($address->country)->toBe('GB')
        ->and($address->display)->toBe('-')
        ->and($address->city)->toBeNull()
        ->and($address->postalCode)->toBeNull();
});
