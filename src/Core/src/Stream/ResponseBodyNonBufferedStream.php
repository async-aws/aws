<?php

declare(strict_types=1);

namespace AsyncAws\Core\Stream;

use AsyncAws\Core\Exception\LogicException;
use AsyncAws\Core\Exception\RuntimeException;

/**
 * One-shot stream backed directly by an HTTP response body resource.
 */
final class ResponseBodyNonBufferedStream implements ReadOnceResultStream, ResultStream
{
    /**
     * @var resource
     */
    private $resource;

    /**
     * @var bool
     */
    private $consumed = false;

    /**
     * @param resource $resource
     */
    public function __construct($resource)
    {
        if (!\is_resource($resource)) {
            throw new RuntimeException('The response stream is not a valid resource.');
        }

        $this->resource = $resource;
    }

    public function __toString(): string
    {
        return $this->getContentAsString();
    }

    /**
     * {@inheritdoc}
     */
    public function getChunks(): iterable
    {
        $this->markConsumed();

        return $this->doGetChunks();
    }

    /**
     * {@inheritdoc}
     */
    public function getContentAsString(): string
    {
        $this->markConsumed();

        $content = stream_get_contents($this->resource);
        if (false === $content) {
            throw new RuntimeException('Unable to read the response stream.');
        }

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function getContentAsResource()
    {
        $this->markConsumed();

        return $this->resource;
    }

    /**
     * @return \Generator<string>
     */
    private function doGetChunks(): \Generator
    {
        while (!feof($this->resource)) {
            $chunk = fread($this->resource, 64 * 1024);
            if (false === $chunk) {
                throw new RuntimeException('Unable to read the response stream.');
            }

            if ('' !== $chunk) {
                yield $chunk;
            }
        }
    }

    private function markConsumed(): void
    {
        if ($this->consumed) {
            throw new LogicException('The non-buffered response stream has already been consumed.');
        }

        $this->consumed = true;
    }
}
