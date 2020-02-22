<?php

declare(strict_types=1);

namespace AsyncAws\Core\Test\Http;

use Symfony\Contracts\HttpClient\ResponseInterface;

class SimpleMockedResponse implements ResponseInterface
{
    private $headers = [];

    private $content = '';

    private $statusCode;

    /**
     * @param array $headers ['name'=>'value']
     */
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
        $info = [
            'response_headers' => $this->getFlatHeaders(),
            'http_code' => $this->statusCode,
        ];

        if (null === $type) {
            return $info;
        }

        if (isset($info[$type])) {
            return $info[$type];
        }

        return 'info: ' . $type;
    }

    private function getFlatHeaders()
    {
        $flat = [];
        foreach ($this->headers as $name => $value) {
            $flat[] = sprintf('%s: %s', $name, $value);
        }

        return $flat;
    }
}
