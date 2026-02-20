<?php

declare(strict_types=1);

use Saloon\Exceptions\Request\ClientException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Uptick\PhpSdk\Uptick\Requests\Auth\GetAccessTokenRequest;
use Uptick\PhpSdk\Uptick\Requests\Properties\ListPropertiesRequest;

it('lists properties successfully', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListPropertiesRequest::class => MockResponse::fixture('list_properties'),
    ]);

    $request = new ListPropertiesRequest;
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Uptick\PhpSdk\Uptick\Data\Properties\PropertyListResponse::class)
        ->and($dto->properties)->toHaveCount(2)
        ->and($dto->properties[0]->id)->toBe('1')
        ->and($dto->properties[0]->type)->toBe('Property')
        ->and($dto->properties[0]->attributes->name)->toBe('Test Property 1')
        ->and($dto->links)->not->toBeNull()
        ->and($dto->meta)->not->toBeNull();
});

it('includes pagination query parameters', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListPropertiesRequest::class => MockResponse::fixture('list_properties'),
    ]);

    $request = new ListPropertiesRequest(page: 2, perPage: 25);
    $response = $this->sdk->send($request);

    expect($request->query()->get('page[limit]'))->toBe(25)
        ->and($request->query()->get('page[offset]'))->toBe(25);
});

it('handles property list errors', function () {
    MockClient::destroyGlobal();
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListPropertiesRequest::class => MockResponse::make(
            body: ['error' => 'Unauthorized'],
            status: 401
        ),
    ]);

    expect(fn () => $this->sdk->send(new ListPropertiesRequest))
        ->toThrow(ClientException::class);
});
