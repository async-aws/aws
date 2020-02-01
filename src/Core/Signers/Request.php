<?php

namespace AsyncAws\Core\Signers;

/**
 * Dummy object to store a Request.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
final class Request
{
    private $method;
    private $url;
    private $headers;
    private $body;

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
