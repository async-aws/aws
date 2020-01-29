<?php

declare(strict_types=1);

namespace WorkingTitle\Aws;

/**
 * Plain old generic HTTP result.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class GenericHttpResult
{
    /**
     * @var int
     */
    private $statusCode;
    /**
     * @var array
     */
    private $headers;
    /**
     * @var string
     */
    private $body;

    public function __construct(string $body, array $headers, int $statusCode)
    {
        $this->body = $body;
        $this->headers = $headers;
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return string[][] The headers of the response keyed by header names in lowercase
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
