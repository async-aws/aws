<?php

namespace AsyncAws\Core\Signers;

/**
 * Dummy object to store a Request.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
final class Request
{
    private $method;

    private $url;

    private $headers;

    private $body;

    /**
     * @param string[]|string[][] $headers
     */
    public function __construct(string $method, string $url, array $headers, string $body)
    {
        $this->method = $method;
        $this->url = $url;
        $this->headers = $headers;
        $this->body = $body;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function hasHeader($name): bool
    {
        return \array_key_exists($name, $this->headers);
    }

    public function setHeader($name, $value): void
    {
        $this->headers[$name] = $value;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
