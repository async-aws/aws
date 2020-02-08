<?php

declare(strict_types=1);

namespace AsyncAws\Core\Test\Http;

use Symfony\Contracts\HttpClient\ResponseInterface;

class SimpleMockedResponse implements ResponseInterface
{
    private $headers = [];

    private $content = '';

    public function __construct(string $content = '', array $headers = [])
    {
        $this->content = $content;
        $this->headers = $headers;
    }

    public function getStatusCode(): int
    {
        return 200;
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
        throw new \LogicException('Not implemented');
    }
}
