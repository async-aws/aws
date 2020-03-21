<?php

namespace AsyncAws\Core;

use AsyncAws\Core\Exception\LogicException;
use AsyncAws\Core\Stream\Stream;

/**
 * Representation of a AWS Request.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class Request
{
    private $method;

    private $uri;

    private $headers;

    private $body;

    private $query;

    private $endpoint;

    /**
     * @param string[]|string[][] $headers
     */
    public function __construct(string $method, string $uri, array $query, array $headers, Stream $body)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->headers = [];
        foreach ($headers as $key => $value) {
            $this->headers[\strtolower($key)] = $value;
        }
        $this->body = $body;
        $this->query = $query;
        $this->endpoint = '';
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
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

    public function getQuery(): array
    {
        return $this->query;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function setEndpoint(string $endpoint): void
    {
        if (!empty($this->endpoint)) {
            throw new LogicException('Request::$endpoint cannot be changed after it has a value.');
        }
        $this->endpoint = $endpoint;
    }
}
