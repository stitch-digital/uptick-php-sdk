<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Uptick\PhpSdk\Uptick\Requests\Auth\GetAccessTokenRequest;
use Uptick\PhpSdk\Uptick\Requests\Clients\ListClientsRequest;

it('lists clients successfully', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListClientsRequest::class => MockResponse::fixture('list_clients'),
    ]);

    $request = new ListClientsRequest;
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Uptick\PhpSdk\Uptick\Data\Clients\ClientListResponse::class)
        ->and($dto->clients)->toHaveCount(2)
        ->and($dto->clients[0]->id)->toBe('1')
        ->and($dto->clients[0]->attributes->name)->toBe('Test Client 1')
        ->and($dto->links)->not->toBeNull()
        ->and($dto->meta)->not->toBeNull();
});

it('includes pagination query parameters', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListClientsRequest::class => MockResponse::fixture('list_clients'),
    ]);

    $request = new ListClientsRequest(page: 2, perPage: 25);
    $response = $this->sdk->send($request);

    expect($request->query()->get('page[limit]'))->toBe(25)
        ->and($request->query()->get('page[offset]'))->toBe(25);
});

it('handles client list errors', function () {
    MockClient::destroyGlobal();
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListClientsRequest::class => MockResponse::make(
            body: ['error' => 'Unauthorized'],
            status: 401
        ),
    ]);

    expect(fn () => $this->sdk->send(new ListClientsRequest))
        ->toThrow(Uptick\PhpSdk\Uptick\Exceptions\UptickException::class);
});
