<?php

declare(strict_types=1);

namespace Uptick\PhpSdk\Uptick;

use DateTimeImmutable;
use Exception;
use InvalidArgumentException;
use Saloon\Contracts\Authenticator;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Saloon\Traits\Plugins\HasTimeout;
use Throwable;
use Uptick\PhpSdk\Uptick\Concerns\SupportsClientsEndpoints;
use Uptick\PhpSdk\Uptick\Exceptions\UptickException;
use Uptick\PhpSdk\Uptick\Exceptions\ValidationException;
use Uptick\PhpSdk\Uptick\Paginators\UptickPaginator;
use Uptick\PhpSdk\Uptick\Requests\Auth\GetAccessTokenRequest;
use Uptick\PhpSdk\Uptick\Requests\Auth\RefreshAccessTokenRequest;

final class Uptick extends \Saloon\Http\Connector implements HasPagination
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;
    use HasTimeout;
    use SupportsClientsEndpoints;

    /**
     * Cached authenticator instance.
     */
    private static ?UptickAuthenticator $cachedAuthenticator = null;

    /**
     * Request timeout in seconds.
     */
    private int $requestTimeout;

    /**
     * Constructor
     */
    public function __construct(
        private string $baseUrl,
        private string $username,
        private string $password,
        private string $clientId,
        private string $clientSecret,
        int $requestTimeout = 10
    ) {
        $this->requestTimeout = $requestTimeout;
    }

    /**
     * Create a new Uptick instance.
     *
     * @param  mixed  ...$arguments  Arguments: baseUrl (string), username (string), password (string), clientId (string), clientSecret (string), requestTimeout (int, optional, default: 10)
     */
    public static function make(mixed ...$arguments): static
    {
        return new self(
            $arguments[0] ?? throw new InvalidArgumentException('baseUrl is required'),
            $arguments[1] ?? throw new InvalidArgumentException('username is required'),
            $arguments[2] ?? throw new InvalidArgumentException('password is required'),
            $arguments[3] ?? throw new InvalidArgumentException('clientId is required'),
            $arguments[4] ?? throw new InvalidArgumentException('clientSecret is required'),
            $arguments[5] ?? 10
        );
    }

    /**
     * Get the request timeout.
     */
    public function getRequestTimeout(): float
    {
        return (float) $this->requestTimeout;
    }

    /**
     * The Base URL of the API.
     */
    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Get an access token using the password grant.
     */
    public function getAccessToken(): UptickAuthenticator
    {
        $request = new GetAccessTokenRequest(
            $this->username,
            $this->password,
            $this->clientId,
            $this->clientSecret
        );

        $response = $this->send($request);

        $data = $response->json();

        $accessToken = $data['access_token'] ?? '';
        $refreshToken = $data['refresh_token'] ?? null;
        $expiresIn = $data['expires_in'] ?? null;

        $expiresAt = null;
        if ($expiresIn !== null) {
            $expiresAt = (new DateTimeImmutable)->modify(sprintf('+%s seconds', $expiresIn));
        }

        return new UptickAuthenticator($accessToken, $refreshToken, $expiresAt);
    }

    /**
     * Refresh an access token using a refresh token.
     */
    public function refreshAccessToken(string $refreshToken): UptickAuthenticator
    {
        $request = new RefreshAccessTokenRequest(
            $refreshToken,
            $this->clientId,
            $this->clientSecret
        );

        $response = $this->send($request);

        $data = $response->json();

        $accessToken = $data['access_token'] ?? '';
        $newRefreshToken = $data['refresh_token'] ?? $refreshToken; // Use existing if not provided
        $expiresIn = $data['expires_in'] ?? null;

        $expiresAt = null;
        if ($expiresIn !== null) {
            $expiresAt = (new DateTimeImmutable)->modify(sprintf('+%s seconds', $expiresIn));
        }

        return new UptickAuthenticator($accessToken, $newRefreshToken, $expiresAt);
    }

    /**
     * Paginate a request.
     */
    public function paginate(Request $request): UptickPaginator
    {
        return new UptickPaginator($this, $request);
    }

    /**
     * Get request exception for error handling.
     */
    public function getRequestException(Response $response, ?Throwable $senderException): Throwable
    {
        if ($response->status() === 422) {
            return new ValidationException($response);
        }

        return new UptickException($response);
    }

    /**
     * Default authenticator with automatic token management.
     */
    protected function defaultAuth(): Authenticator
    {
        if (! self::$cachedAuthenticator instanceof UptickAuthenticator || self::$cachedAuthenticator->hasExpired()) {
            self::$cachedAuthenticator = $this->getOrRefreshToken();
        }

        return self::$cachedAuthenticator;
    }

    /**
     * Get or refresh the access token automatically.
     */
    private function getOrRefreshToken(): UptickAuthenticator
    {
        // Try to refresh if we have a refreshable token that expired
        if (self::$cachedAuthenticator?->isRefreshable() && self::$cachedAuthenticator->hasExpired()) {
            $refreshToken = self::$cachedAuthenticator->getRefreshToken();
            if ($refreshToken !== null) {
                try {
                    return $this->refreshAccessToken($refreshToken);
                } catch (Exception) {
                    // If refresh fails, fall through to get a new token
                }
            }
        }

        // Get a new access token
        return $this->getAccessToken();
    }
}
