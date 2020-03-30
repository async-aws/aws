<?php

declare(strict_types=1);

namespace AsyncAws\Core\Stream;

use Symfony\Contracts\HttpClient\ResponseStreamInterface;

/**
 * Stream a HTTP response body.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class ResponseBodyStream implements ResultStream
{
    /**
     * @var ResponseStreamInterface
     */
    private $responseStream;

    /**
     * @var resource|null
     */
    private $resource;

    /**
     * @var ResponseBodyResourceStream|null
     */
    private $fallback;

    public function __construct(ResponseStreamInterface $responseStream)
    {
        $this->responseStream = $responseStream;
    }

    public function __toString()
    {
        return $this->getContentAsString();
    }

    /**
     * {@inheritdoc}
     */
    public function getChunks(): iterable
    {
        if (null !== $this->fallback) {
            return $this->fallback->getChunks();
        }

        if (null === $this->resource) {
            $this->resource = \fopen('php://temp', 'rb+');
        }
        foreach ($this->responseStream as $chunk) {
            $chunkContent = $chunk->getContent();
            \fwrite($this->resource, $chunkContent);
            yield $chunkContent;
        }

        $this->fallback = new ResponseBodyResourceStream($this->resource);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentAsString(): string
    {
        if (null === $this->fallback) {
            // Use getChunks() to read stream content to $this->fallback
            foreach ($this->getChunks() as $chunk) {
            }
        }

        return $this->fallback->getContentAsString();
    }

    /**
     * {@inheritdoc}
     */
    public function getContentAsResource()
    {
        if (null === $this->fallback) {
            // Use getChunks() to read stream content to $this->fallback
            foreach ($this->getChunks() as $chunk) {
            }
        }

        return $this->fallback->getContentAsResource();
    }
}
