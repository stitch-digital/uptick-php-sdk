<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Uptick\PhpSdk\Uptick\Data\Properties\Address;
use Uptick\PhpSdk\Uptick\Data\Properties\Property;
use Uptick\PhpSdk\Uptick\Data\Properties\PropertyAttributes;
use Uptick\PhpSdk\Uptick\Paginators\UptickPaginator;
use Uptick\PhpSdk\Uptick\Requests\Auth\GetAccessTokenRequest;
use Uptick\PhpSdk\Uptick\Requests\Properties\ListPropertiesRequest;

it('lists properties via SDK method', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListPropertiesRequest::class => MockResponse::fixture('list_properties'),
    ]);

    $paginator = $this->sdk->properties()->list();

    expect($paginator)->toBeInstanceOf(UptickPaginator::class);
});

it('iterates over paginated properties', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListPropertiesRequest::class => MockResponse::fixture('list_properties'),
    ]);

    $paginator = $this->sdk->properties()->list();
    $properties = iterator_to_array($paginator->items());

    expect($properties)->toHaveCount(2)
        ->and($properties[0])->toBeInstanceOf(Property::class)
        ->and($properties[0]->id)->toBe('1')
        ->and($properties[0]->type)->toBe('Property')
        ->and($properties[0]->attributes)->toBeInstanceOf(PropertyAttributes::class)
        ->and($properties[0]->attributes->name)->toBe('Test Property 1')
        ->and($properties[0]->attributes->ref)->toBe('TP001')
        ->and($properties[0]->attributes->status)->toBe('ACTIVE')
        ->and($properties[1]->id)->toBe('2')
        ->and($properties[1]->attributes->name)->toBe('Test Property 2');
});

it('parses address fields correctly', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListPropertiesRequest::class => MockResponse::fixture('list_properties'),
    ]);

    $paginator = $this->sdk->properties()->list();
    $properties = iterator_to_array($paginator->items());

    $address = $properties[0]->attributes->address;

    expect($address)->toBeInstanceOf(Address::class)
        ->and($address->country)->toBe('AU')
        ->and($address->display)->toBe('123 Test Street, Sydney NSW 2000')
        ->and($address->streetline)->toBe('123 Test Street')
        ->and($address->city)->toBe('Sydney')
        ->and($address->state)->toBe('NSW')
        ->and($address->countryName)->toBe('Australia')
        ->and($address->postalCode)->toBe('2000');
});

it('parses DateTime fields correctly', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListPropertiesRequest::class => MockResponse::fixture('list_properties'),
    ]);

    $paginator = $this->sdk->properties()->list();
    $properties = iterator_to_array($paginator->items());

    expect($properties[0]->attributes->created)->toBeInstanceOf(\DateTimeImmutable::class)
        ->and($properties[0]->attributes->updated)->toBeInstanceOf(\DateTimeImmutable::class)
        ->and($properties[0]->attributes->created->format('Y-m-d'))->toBe('2024-01-15')
        ->and($properties[0]->attributes->updated->format('Y-m-d'))->toBe('2024-02-20');
});

it('parses numeric fields correctly', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListPropertiesRequest::class => MockResponse::fixture('list_properties'),
    ]);

    $paginator = $this->sdk->properties()->list();
    $properties = iterator_to_array($paginator->items());

    expect($properties[0]->attributes->buildingSizesqm)->toBe(5000.5)
        ->and($properties[0]->attributes->buildingStoreysAboveGround)->toBe(10)
        ->and($properties[0]->attributes->buildingStoreysBelowGround)->toBe(2)
        ->and($properties[0]->attributes->buildingTenancies)->toBe(15)
        ->and($properties[1]->attributes->buildingSizesqm)->toBeNull()
        ->and($properties[1]->attributes->buildingStoreysAboveGround)->toBeNull();
});

it('parses date-only fields correctly', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListPropertiesRequest::class => MockResponse::fixture('list_properties'),
    ]);

    $paginator = $this->sdk->properties()->list();
    $properties = iterator_to_array($paginator->items());

    expect($properties[0]->attributes->reviewDate)->toBeInstanceOf(\DateTimeImmutable::class)
        ->and($properties[0]->attributes->reviewDate->format('Y-m-d'))->toBe('2024-06-15')
        ->and($properties[1]->attributes->reviewDate)->toBeNull();
});

it('parses relationships correctly', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListPropertiesRequest::class => MockResponse::fixture('list_properties'),
    ]);

    $paginator = $this->sdk->properties()->list();
    $properties = iterator_to_array($paginator->items());

    expect($properties[0]->relationships)->toBeArray()
        ->and($properties[0]->relationships['client']['type'])->toBe('Client')
        ->and($properties[0]->relationships['client']['id'])->toBe('1')
        ->and($properties[0]->relationships['account_manager']['type'])->toBe('User')
        ->and($properties[0]->relationships)->not->toHaveKey('billingcard');
});

it('filters properties by client', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListPropertiesRequest::class => MockResponse::fixture('list_properties'),
    ]);

    $paginator = $this->sdk->properties()->whereClient(1);

    expect($paginator)->toBeInstanceOf(UptickPaginator::class);
});

it('filters active properties', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListPropertiesRequest::class => MockResponse::fixture('list_properties'),
    ]);

    $paginator = $this->sdk->properties()->listActive();

    expect($paginator)->toBeInstanceOf(UptickPaginator::class);
});

it('searches properties by text', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListPropertiesRequest::class => MockResponse::fixture('list_properties'),
    ]);

    $paginator = $this->sdk->properties()->search('Test');

    expect($paginator)->toBeInstanceOf(UptickPaginator::class);
});

it('finds property by id', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListPropertiesRequest::class => MockResponse::fixture('list_properties'),
    ]);

    $paginator = $this->sdk->properties()->findById(1);

    expect($paginator)->toBeInstanceOf(UptickPaginator::class);
});

it('filters properties by status', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListPropertiesRequest::class => MockResponse::fixture('list_properties'),
    ]);

    $paginator = $this->sdk->properties()->whereStatus('ACTIVE');

    expect($paginator)->toBeInstanceOf(UptickPaginator::class);
});

it('filters properties by branch', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListPropertiesRequest::class => MockResponse::fixture('list_properties'),
    ]);

    $paginator = $this->sdk->properties()->whereBranch(2);

    expect($paginator)->toBeInstanceOf(UptickPaginator::class);
});

it('filters properties by zone', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListPropertiesRequest::class => MockResponse::fixture('list_properties'),
    ]);

    $paginator = $this->sdk->properties()->whereZone(3);

    expect($paginator)->toBeInstanceOf(UptickPaginator::class);
});
