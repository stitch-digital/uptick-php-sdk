<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Uptick\PhpSdk\Uptick\Requests\Auth\GetAccessTokenRequest;
use Uptick\PhpSdk\Uptick\Requests\Clients\ListClientsRequest;

it('lists clients via SDK method', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListClientsRequest::class => MockResponse::fixture('list_clients'),
    ]);

    $paginator = $this->sdk->listClients();

    expect($paginator)->toBeInstanceOf(Uptick\PhpSdk\Uptick\Paginators\UptickPaginator::class);
});

it('iterates over paginated clients', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListClientsRequest::class => MockResponse::fixture('list_clients'),
    ]);

    $paginator = $this->sdk->listClients();
    $clients = iterator_to_array($paginator->items());

    expect($clients)->toHaveCount(2)
        ->and($clients[0])->toBeInstanceOf(Uptick\PhpSdk\Uptick\Data\Clients\Client::class)
        ->and($clients[0]->id)->toBe('1')
        ->and($clients[0]->attributes->name)->toBe('Test Client 1')
        ->and($clients[1]->id)->toBe('2')
        ->and($clients[1]->attributes->name)->toBe('Test Client 2');
});

it('supports custom pagination parameters', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
        ListClientsRequest::class => MockResponse::fixture('list_clients'),
    ]);

    $paginator = $this->sdk->listClients(page: 2, perPage: 25);

    expect($paginator)->toBeInstanceOf(Uptick\PhpSdk\Uptick\Paginators\UptickPaginator::class);
});
