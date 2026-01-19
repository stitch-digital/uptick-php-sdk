<?php

declare(strict_types=1);

namespace Uptick\PhpSdk\Uptick\Exceptions;

use Exception;
use Saloon\Http\Response;
use Throwable;

class UptickException extends Exception
{
    public function __construct(
        public readonly Response $response,
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        if (empty($message)) {
            $message = sprintf(
                'API request failed with status %d: %s',
                $response->status(),
                $response->body()
            );
        }

        parent::__construct($message, $code, $previous);
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}
