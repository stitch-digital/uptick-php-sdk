# Uptick PHP SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/stitch-digital/uptick-php-sdk.svg?style=flat-square)](https://packagist.org/packages/stitch-digital/uptick-php-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/stitch-digital/uptick-php-sdk.svg?style=flat-square)](https://packagist.org/packages/stitch-digital/uptick-php-sdk)

This package is an unofficial PHP SDK for the Uptick API, built with [Saloon](https://docs.saloon.dev/) v3.

```php
use Uptick\PhpSdk\Uptick\Uptick;

// List clients with pagination
$paginator = Uptick::make(
    baseUrl: 'https://api.uptick.example.com',
    username: 'your_username',
    password: 'your_password',
    clientId: 'your_client_id',
    clientSecret: 'your_client_secret'
)->listClients();

foreach ($paginator->items() as $client) {
    echo $client->attributes->name . "\n";
}
```

Behind the scenes, the SDK uses [Saloon](https://docs.saloon.dev) to make the HTTP requests.

## Installation

```bash
composer require stitch-digital/uptick-php-sdk
```

## Quick Start

```php
use Uptick\PhpSdk\Uptick\Uptick;

// Create an SDK instance
$client = Uptick::make(
    baseUrl: 'https://api.uptick.example.com',
    username: 'your_username',
    password: 'your_password',
    clientId: 'your_client_id',
    clientSecret: 'your_client_secret'
);

// List clients (paginated)
$paginator = $client->listClients();

// Iterate over all clients
foreach ($paginator->items() as $client) {
    echo "{$client->attributes->name} ({$client->id})\n";
}

// Or collect all items
$allClients = $paginator->collect()->all();
```

## Usage

### Authentication

The SDK handles OAuth2 authentication automatically. You provide your credentials when creating the SDK instance:

```php
$client = Uptick::make(
    baseUrl: 'https://api.uptick.example.com',
    username: 'your_username',
    password: 'your_password',
    clientId: 'your_client_id',
    clientSecret: 'your_client_secret'
);
```

The SDK will automatically:
- Obtain an access token using the password grant
- Refresh the token when it expires
- Include the token in all API requests

### Setting a timeout

By default, the SDK waits 10 seconds for a response. Override via the constructor:

```php
$client = new Uptick(
    baseUrl: 'https://api.uptick.example.com',
    username: 'your_username',
    password: 'your_password',
    clientId: 'your_client_id',
    clientSecret: 'your_client_secret',
    requestTimeout: 30
);
```

### Handling errors

The SDK will throw an exception if the API returns an error. For validation errors, the SDK will throw a `ValidationException`.

```php
use Uptick\PhpSdk\Uptick\Exceptions\ValidationException;
use Uptick\PhpSdk\Uptick\Exceptions\UptickException;

try {
    $client->listClients();
} catch (ValidationException $exception) {
    $exception->getMessage(); // returns a string describing the errors
    
    $exception->getErrors(); // returns an array with all validation errors
    $exception->getErrorsForField('field_name'); // get errors for a specific field
} catch (UptickException $exception) {
    $exception->getMessage();
    $exception->getResponse(); // access the Saloon Response object for debugging
}
```

## Using Saloon requests directly

You can use the request classes directly for full control:

```php
use Uptick\PhpSdk\Uptick\Uptick;
use Uptick\PhpSdk\Uptick\Requests\Clients\ListClientsRequest;

$client = Uptick::make(...);
$request = new ListClientsRequest();

$response = $client->send($request)->dto();
```

## Security

If you discover any security related issues, please email support@stitch-digital.com instead of using the issue tracker.

## Credits

- [Stitch Digital](https://www.stitch-digital.com)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
