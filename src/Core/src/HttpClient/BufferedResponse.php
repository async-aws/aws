<?php

namespace AsyncAws\Core\HttpClient;

use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\JsonException;
use Symfony\Component\HttpClient\Exception\RedirectionException;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Keeps the body of a non-buffered response so it can be read more than once.
 *
 * @internal
 */
final class BufferedResponse implements ResponseInterface
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var string|null
     */
    private $content;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function getHeaders(bool $throw = true): array
    {
        if ($throw) {
            $this->checkStatusCode();
        }

        return $this->response->getHeaders(false);
    }

    public function getContent(bool $throw = true): string
    {
        if ($throw) {
            $this->checkStatusCode();
        }

        return $this->content ??= $this->response->getContent(false);
    }

    public function toArray(bool $throw = true): array
    {
        try {
            $content = json_decode($this->getContent($throw), true, 512, \JSON_BIGINT_AS_STRING | \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new JsonException($e->getMessage(), $e->getCode(), $e);
        }

        if (!\is_array($content)) {
            throw new JsonException(\sprintf('JSON content was expected to decode to an array, "%s" returned.', get_debug_type($content)));
        }

        return $content;
    }

    public function cancel(): void
    {
        $this->response->cancel();
    }

    public function getInfo(?string $type = null): mixed
    {
        return $this->response->getInfo($type);
    }

    private function checkStatusCode(): void
    {
        $code = $this->getStatusCode();

        if (500 <= $code) {
            throw new ServerException($this);
        }

        if (400 <= $code) {
            throw new ClientException($this);
        }

        if (300 <= $code) {
            throw new RedirectionException($this);
        }
    }
}
