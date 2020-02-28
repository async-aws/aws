<?php

namespace AsyncAws\Core\Signer;

use AsyncAws\Core\Stream\Stream;

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
    public function __construct(string $method, string $url, array $headers, Stream $body)
    {
        $this->method = $method;
        $this->url = $url;
        foreach ($headers as $key => $value) {
            $this->headers[\strtolower($key)] = $value;
        }
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
        return \array_key_exists(strtolower($name), $this->headers);
    }

    public function setHeader($name, $value): void
    {
        $this->headers[strtolower($name)] = $value;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return string|string[]
     */
    public function getHeader(string $name)
    {
        return $this->headers[strtolower($name)] ?? null;
    }

    public function getBody(): Stream
    {
        return $this->body;
    }

    public function setBody(Stream $body)
    {
        $this->body = $body;
    }
}
