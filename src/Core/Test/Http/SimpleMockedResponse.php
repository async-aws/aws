<?php

declare(strict_types=1);

namespace AsyncAws\Core\Test\Http;

use Symfony\Contracts\HttpClient\ResponseInterface;

class SimpleMockedResponse implements ResponseInterface
{
    private $headers = [];

    private $content = '';

    private $statusCode;

    public function __construct(string $content = '', array $headers = [], int $statusCode = 200)
    {
        $this->content = $content;
        $this->headers = $headers;
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(bool $throw = true): array
    {
        return $this->headers;
    }

    public function getContent(bool $throw = true): string
    {
        return $this->content;
    }

    public function toArray(bool $throw = true): array
    {
        throw new \LogicException('Not implemented');
    }

    public function cancel(): void
    {
        throw new \LogicException('Not implemented');
    }

    public function getInfo(string $type = null)
    {
        if (null === $type || 'response_headers' === $type) {
            return [];
        }

        if ('http_code' === $type) {
            return $this->statusCode;
        }

        return 'info: ' . $type;
    }
}
