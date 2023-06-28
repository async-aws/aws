<?php

namespace AsyncAws\Core;

use AsyncAws\Core\Exception\LogicException;
use AsyncAws\Core\Stream\RequestStream;

/**
 * Representation of an HTTP Request.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class Request
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var array<string, string>
     */
    private $headers;

    /**
     * @var RequestStream
     */
    private $body;

    /**
     * @var array<string, string>
     */
    private $query;

    /**
     * @var string
     */
    private $endpoint;

    private $parsed;

    /**
     * @param array<string, string> $query
     * @param array<string, string> $headers
     */
    public function __construct(string $method, string $uri, array $query, array $headers, RequestStream $body)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->headers = [];
        foreach ($headers as $key => $value) {
            $this->headers[strtolower($key)] = (string) $value;
        }
        $this->body = $body;
        $this->query = $query;
        $this->endpoint = '';
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function hasHeader(string $name): bool
    {
        return \array_key_exists(strtolower($name), $this->headers);
    }

    public function setHeader(string $name, string $value): void
    {
        $this->headers[strtolower($name)] = $value;
    }

    /**
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getHeader(string $name): ?string
    {
        return $this->headers[strtolower($name)] ?? null;
    }

    public function removeHeader(string $name): void
    {
        unset($this->headers[strtolower($name)]);
    }

    public function getBody(): RequestStream
    {
        return $this->body;
    }

    public function setBody(RequestStream $body): void
    {
        $this->body = $body;
    }

    public function hasQueryAttribute(string $name): bool
    {
        return \array_key_exists($name, $this->query);
    }

    public function removeQueryAttribute(string $name): void
    {
        unset($this->query[$name]);
        $this->endpoint = '';
    }

    public function setQueryAttribute(string $name, string $value): void
    {
        $this->query[$name] = $value;
        $this->endpoint = '';
    }

    public function getQueryAttribute(string $name): ?string
    {
        return $this->query[$name] ?? null;
    }

    /**
     * @return array<string, string>
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    public function getEndpoint(): string
    {
        if (empty($this->endpoint)) {
            $this->endpoint = $this->parsed['scheme'] . '://' . $this->parsed['host'] . (isset($this->parsed['port']) ? ':' . $this->parsed['port'] : '') . $this->uri . ($this->query ? (false === strpos($this->uri, '?') ? '?' : '&') . http_build_query($this->query, '', '&', \PHP_QUERY_RFC3986) : '');
        }

        return $this->endpoint;
    }

    public function setEndpoint(string $endpoint): void
    {
        if (!empty($this->endpoint)) {
            throw new LogicException('Request::$endpoint cannot be changed after it has a value.');
        }

        $this->endpoint = $endpoint;
        $this->parsed = parse_url($this->endpoint);
        parse_str($this->parsed['query'] ?? '', $this->query);
        $this->uri = $this->parsed['path'] ?? '/';
    }
}
