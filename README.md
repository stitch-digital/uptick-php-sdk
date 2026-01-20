# Uptick PHP SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/stitch-digital/uptick-php-sdk.svg?style=flat-square)](https://packagist.org/packages/stitch-digital/uptick-php-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/stitch-digital/uptick-php-sdk.svg?style=flat-square)](https://packagist.org/packages/stitch-digital/uptick-php-sdk)

This package is an unofficial PHP SDK for the Uptick API, built with [Saloon](https://docs.saloon.dev/) v3.

<div align="center">
  <a href="https://www.uptickhq.com/">
    <img src="uptick-logo.webp" alt="Uptick Logo">
  </a>
</div>

## About Uptick

[Uptick](https://www.uptickhq.com/) is a cloud-based field service and compliance platform designed specifically for fire protection, security, HVAC and related maintenance businesses, helping them manage inspections, scheduling, asset maintenance and reporting in one place. It replaces paper and manual processes with a mobile-friendly system that lets technicians capture data, photos and defect details on site while office teams handle job scheduling, quoting, invoicing and customer communication more efficiently, all backed by built-in standards and integrations with tools like accounting software. Uptick has expanded into the UK market to support local fire protection businesses with its purpose-built tools for compliance and workforce management.

- **Website:** [https://www.uptickhq.com/](https://www.uptickhq.com/)
- **GitHub:** [https://github.com/uptick](https://github.com/uptick)

```php
use Uptick\PhpSdk\Uptick\Uptick;

$uptick = Uptick::make(
    baseUrl: 'https://api.uptick.example.com',
    username: 'your_username',
    password: 'your_password',
    clientId: 'your_client_id',
    clientSecret: 'your_client_secret'
);

$paginator = $uptick->clients()->list();

foreach ($paginator->items() as $client) {
    // e.g. Acme Corporation
    $clientName = $client->attributes->name;
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
$uptick = Uptick::make(
    baseUrl: 'https://api.uptick.example.com',
    username: 'your_username',
    password: 'your_password',
    clientId: 'your_client_id',
    clientSecret: 'your_client_secret'
);

// List clients (paginated)
$paginator = $uptick->clients()->list();

// Iterate over all clients
foreach ($paginator->items() as $client) {
    $clientId = $client->id;
    $clientName = $client->attributes->name;
}

// Or collect all items into an array
$allClients = $paginator->collect()->all();
```

## Usage

### Authentication

The SDK handles OAuth2 authentication automatically. You provide your credentials when creating the SDK instance:

```php
$uptick = Uptick::make(
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
$uptick = new Uptick(
    baseUrl: 'https://api.uptick.example.com',
    username: 'your_username',
    password: 'your_password',
    clientId: 'your_client_id',
    clientSecret: 'your_client_secret',
    requestTimeout: 30
);
```

### Handling errors

The SDK uses Saloon's `AlwaysThrowOnErrors` trait on the connector, which means exceptions will automatically be thrown whenever a request fails (4xx or 5xx response status codes). You don't need to manually check if a request failed or call `throw()` on responses - exceptions are thrown automatically.

Saloon's built-in exceptions are used for most errors, with a custom exception for validation errors (422 status codes).

#### Exception Hierarchy

The SDK uses Saloon's exception hierarchy:

```
SaloonException
├── FatalRequestException (Connection Errors)
└── RequestException (Request Errors)
    ├── ServerException (5xx)
    │   ├── InternalServerErrorException (500)
    │   ├── ServiceUnavailableException (503)
    │   └── GatewayTimeoutException (504)
    └── ClientException (4xx)
        ├── UnauthorizedException (401)
        ├── PaymentRequiredException (402)
        ├── ForbiddenException (403)
        ├── NotFoundException (404)
        ├── MethodNotAllowedException (405)
        ├── RequestTimeOutException (408)
        ├── UnprocessableEntityException (422)
        │   └── ValidationException (422 - Custom)
        └── TooManyRequestsException (429)
```

#### Validation Errors

For validation errors (422 status code), the SDK throws a custom `ValidationException` which extends Saloon's `UnprocessableEntityException`. This exception provides additional methods to access validation error details:

```php
use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\ServerException;
use Uptick\PhpSdk\Uptick\Exceptions\ValidationException;

try {
    $paginator = $uptick->clients()->list();
} catch (ValidationException $exception) {
    // Get a string describing all errors
    $message = $exception->getMessage();
    
    // Get all validation errors as an array
    $errors = $exception->getErrors();
    // ['field_name' => ['Error message 1', 'Error message 2']]
    
    // Get errors for a specific field
    $fieldErrors = $exception->getErrorsForField('field_name');
    
    // Check if a specific field has errors
    $hasErrors = $exception->hasErrorsForField('field_name');
    
    // Get all error messages as a flat array
    $allMessages = $exception->getAllErrorMessages();
    
    // Access the Saloon Response object for debugging
    $response = $exception->getResponse();
} catch (ClientException $exception) {
    // Handle 4xx errors (401, 403, 404, etc.)
    $message = $exception->getMessage();
    $response = $exception->getResponse();
} catch (ServerException $exception) {
    // Handle 5xx errors
    $message = $exception->getMessage();
    $response = $exception->getResponse();
}
```

#### Connection Errors

If Saloon cannot connect to the API, it will throw a `FatalRequestException`:

```php
use Saloon\Exceptions\Request\FatalRequestException;

try {
    $paginator = $uptick->clients()->list();
} catch (FatalRequestException $exception) {
    // Handle connection errors (network issues, DNS failures, etc.)
    $message = $exception->getMessage();
}
```

## Resources

The SDK provides resource-based APIs for working with different entities. Each resource is accessed through a method on the main SDK instance.

### Client Resource

The client resource provides methods for working with clients. Access it through the `clients()` method:

```php
$uptick = Uptick::make(...);
$clients = $uptick->clients();
```

Methods that return lists of clients (like `list()`, `findById()`, etc.) return a `UptickPaginator` instance. See the [Pagination](#pagination) section for details on working with paginated results. Future methods like `create()`, `update()`, and `delete()` will return different response types.

#### Listing Clients

The following methods return a `UptickPaginator` instance:

**List all clients:**

```php
$paginator = $uptick->clients()->list();
```

**List active clients:**

```php
$paginator = $uptick->clients()->listActive();
```

**List inactive clients:**

```php
$paginator = $uptick->clients()->listInactive();
```

#### Finding Clients

**Find by ID:**

```php
$paginator = $uptick->clients()->findById(123);
```

**Find by name:**

```php
// Exact match
$paginator = $uptick->clients()->findByName('Acme Corp', strict: true);

// Contains match (default)
$paginator = $uptick->clients()->findByName('Acme');
```

**Search clients:**

```php
// Search across multiple fields
$paginator = $uptick->clients()->search('construction');
```

#### Filtering Clients

The client resource provides many filtering methods. All filtering methods return a `UptickPaginator` instance. You can chain multiple filters together, or pass filters directly to the `list()` method for better performance.

**Filtering by Account Manager:**

```php
// Single or multiple account manager IDs
$paginator = $uptick->clients()->whereAccountManager(5);
$paginator = $uptick->clients()->whereAccountManager([5, 10, 15]);

// Exclude account managers
$paginator = $uptick->clients()->whereNotAccountManager(5);
```

**Filtering by Billing Card:**

```php
$paginator = $uptick->clients()->whereBillingCard(3);
$paginator = $uptick->clients()->whereBillingCard([3, 7]);
$paginator = $uptick->clients()->whereNotBillingCard(3);
```

**Filtering by Date:**

```php
// Created dates
$paginator = $uptick->clients()->createdBefore('2024-01-01T00:00:00Z');
$paginator = $uptick->clients()->createdAfter('2024-01-01T00:00:00Z');

// Updated dates
$paginator = $uptick->clients()->updatedBefore('2024-01-01T00:00:00Z');
$paginator = $uptick->clients()->updatedAfter('2024-01-01T00:00:00Z');
$paginator = $uptick->clients()->updatedSince('2024-01-01T00:00:00Z'); // alias
```

**Filtering by Sector:**

```php
use Uptick\PhpSdk\Uptick\Data\Clients\Sector;

// Using Sector enum
$paginator = $uptick->clients()->whereSector(Sector::Construction);
$paginator = $uptick->clients()->whereSector([Sector::Construction, Sector::RetailTrade]);

// Using string (backward compatibility)
$paginator = $uptick->clients()->whereSector('Construction');

// Exclude sectors
$paginator = $uptick->clients()->whereNotSector(Sector::Construction);
```

**Filtering by Price Tier:**

```php
$paginator = $uptick->clients()->wherePriceTier(2);
$paginator = $uptick->clients()->wherePriceTier([2, 4, 6]);
$paginator = $uptick->clients()->whereNotPriceTier(2);
```

**Filtering by Active Status:**

```php
$paginator = $uptick->clients()->whereIsActive(true);
$paginator = $uptick->clients()->whereIsActive(false);
```

**Filtering by Report Settings:**

```php
$paginator = $uptick->clients()->whereReportWhitelabel(true);
$paginator = $uptick->clients()->whereReportManual(true);
```

**Filtering by Billing Settings:**

```php
$paginator = $uptick->clients()->whereBillingManual(true);
$paginator = $uptick->clients()->whereBillingFixedPrice(true);
```

**Filtering by Quoting Settings:**

```php
$paginator = $uptick->clients()->whereQuotingAutoRemindersEnabled(true);
```

**Filtering by Properties:**

```php
$paginator = $uptick->clients()->whereHasProperties(true);
$paginator = $uptick->clients()->whereHasActiveProperty(true);
```

**Filtering by Duplicate Status:**

```php
$paginator = $uptick->clients()->whereIsDuplicated(true);
```

**Filtering by Client Group:**

```php
$paginator = $uptick->clients()->whereParentClientGroup(10);
$paginator = $uptick->clients()->whereClientGroup(10);
$paginator = $uptick->clients()->whereNotClientGroup(10);
```

**Filtering by Branch:**

```php
$paginator = $uptick->clients()->whereBranch(5);
$paginator = $uptick->clients()->whereBranch([5, 10]);
$paginator = $uptick->clients()->whereNotBranch(5);
```

**Filtering by Account:**

```php
$paginator = $uptick->clients()->whereHasAccount(true);
```

**Filtering by Tags:**

```php
$paginator = $uptick->clients()->whereTags(1);
$paginator = $uptick->clients()->whereTags([1, 2, 3]);
$paginator = $uptick->clients()->whereNotTags(1);
```

**Filtering by Business Hours:**

```php
$paginator = $uptick->clients()->whereHasBusinessHours(true);
```

**Filtering by Phone Number:**

```php
$paginator = $uptick->clients()->wherePhoneNumberContains('555');
```

**Filtering by Extra Fields:**

```php
$paginator = $uptick->clients()->whereExtraFields([
    'custom_field_1' => 'value1',
    'custom_field_2' => 'value2',
]);
```

**Custom Filters:**

You can pass custom filters directly to the `list()` method for better performance when using multiple filters:

```php
$paginator = $uptick->clients()->list([
    'is_active' => true,
    'sector' => Sector::Construction->value,
    'account_manager' => [5, 10],
    'created_after' => '2024-01-01T00:00:00Z',
]);
```

**Chaining Filters:**

You can also chain filter methods together, though using `list()` with an array is more efficient:

```php
$paginator = $uptick->clients()
    ->whereIsActive(true)
    ->whereSector(Sector::Construction)
    ->whereAccountManager(5)
    ->createdAfter('2024-01-01T00:00:00Z');
```

#### Client Object Structure

Each client returned from listing methods is a `Client` DTO with the following structure:

```php
$client->id;                    // string - Client ID
$client->type;                  // string - Always "Client"
$client->attributes->name;       // string|null - Client name
$client->attributes->isActive;   // bool|null - Active status
$client->attributes->sector;     // Sector|null - Sector enum
$client->attributes->created;    // DateTimeImmutable|null - Creation date
$client->attributes->updated;    // DateTimeImmutable|null - Last update date
$client->attributes->ref;        // string|null - Reference number
$client->attributes->contactName; // string|null - Contact name
$client->attributes->contactEmail; // string|null - Contact email
// ... and many more properties (see ClientAttributes class)
$client->relationships;         // array - Related resources
```

## Pagination

Many resource methods return a `UptickPaginator` instance for working with paginated API responses. The SDK uses Saloon's pagination plugin to handle pagination automatically. The Uptick API uses limit/offset pagination, where results are divided into pages. The SDK's paginator handles all of this for you, allowing you to iterate through every result across every page in one loop.

The paginator is a custom PHP iterator, meaning it can be used in foreach loops. It's also memory efficient - it only keeps one page in memory at a time, so you can iterate through thousands of pages and millions of results without running out of memory.

### Iterating Over Items

The simplest way to use the paginator is to iterate over items using the `items()` method. This will give you each item across multiple pages:

```php
$paginator = $uptick->clients()->list();

foreach ($paginator->items() as $client) {
    $clientId = $client->id;
    $clientName = $client->attributes->name;
    $isActive = $client->attributes->isActive;
    $sector = $client->attributes->sector?->value;
    $createdAt = $client->attributes->created?->format('Y-m-d H:i:s');
}
```

### Using Laravel Collections

If you're using Laravel (or have `illuminate/collections` installed), you can use the `collect()` method to get a `LazyCollection`. This allows you to use powerful collection methods like `filter()`, `map()`, `sort()`, and more while keeping memory consumption low:

```php
use Uptick\PhpSdk\Uptick\Data\Clients\Sector;

$paginator = $uptick->clients()->list();
$collection = $paginator->collect();

$activeConstructionClients = $collection
    ->filter(function ($client) {
        return $client->attributes->isActive === true 
            && $client->attributes->sector === Sector::Construction;
    })
    ->map(function ($client) {
        return [
            'id' => $client->id,
            'name' => $client->attributes->name,
            'sector' => $client->attributes->sector?->value,
        ];
    })
    ->sortBy('name');

foreach ($activeConstructionClients as $client) {
    $clientName = $client['name'];
}
```

### Collecting All Items

You can collect all items into an array if you need to work with the complete dataset:

```php
$paginator = $uptick->clients()->list();
$allClients = $paginator->collect()->all();

// Now you have an array of all clients
$clientCount = count($allClients);
$firstClient = $allClients[0];
```

### Accessing Pagination Metadata

The paginator provides methods to access pagination information:

```php
$paginator = $uptick->clients()->list();

// Get the total number of pages
$totalPages = $paginator->getTotalPages();

// Get the first item without iterating
$firstClient = $paginator->items()->current();
```

### Controlling Page Size

By default, 50 items are fetched per page. You can control pagination by setting the per-page limit:

```php
$paginator = $uptick->clients()->list();
$paginator->setPerPageLimit(100); // Fetch 100 items per page

foreach ($paginator->items() as $client) {
    $clientName = $client->attributes->name;
}
```

The paginator will automatically handle fetching subsequent pages as you iterate through the results. You don't need to worry about managing page numbers or offsets - just iterate and the SDK handles the rest.

## Using Saloon requests directly

You can use the request classes directly for full control:

```php
use Uptick\PhpSdk\Uptick\Uptick;
use Uptick\PhpSdk\Uptick\Requests\Clients\ListClientsRequest;

$uptick = Uptick::make(...);
$request = new ListClientsRequest();

$response = $uptick->send($request)->dto();
```

## Security

If you discover any security related issues, please email support@stitch-digital.com instead of using the issue tracker.

## Credits

- [Stitch Digital](https://www.stitch-digital.com)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
