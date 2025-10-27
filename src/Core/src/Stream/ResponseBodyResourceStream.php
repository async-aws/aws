<?php

declare(strict_types=1);

namespace AsyncAws\Core\Stream;

use AsyncAws\Core\Exception\RuntimeException;

/**
 * Provides a ResultStream from a resource filled by an HTTP response body.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class ResponseBodyResourceStream implements ResultStream
{
    /**
     * @var resource
     */
    private $resource;

    /**
     * @param resource $resource
     */
    public function __construct($resource)
    {
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
        $pos = ftell($this->resource);
        if (false === $pos) {
            throw new RuntimeException('Unable to read the content position.');
        }

        if (0 !== $pos && !rewind($this->resource)) {
            throw new RuntimeException('The stream is not rewindable');
        }

        try {
            while (!feof($this->resource)) {
                $data = fread($this->resource, 64 * 1024);

                if (false === $data) {
                    throw new RuntimeException('Unable to read the content.');
                }

                yield $data;
            }
        } finally {
            fseek($this->resource, $pos);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getContentAsString(): string
    {
        $pos = ftell($this->resource);

        if (false === $pos) {
            throw new RuntimeException('Failed to read the stream position.');
        }

        try {
            if (!rewind($this->resource)) {
                throw new RuntimeException('Failed to rewind the stream');
            }

            $data = stream_get_contents($this->resource);

            if (false === $data) {
                throw new RuntimeException('Unable to read the content.');
            }

            return $data;
        } finally {
            fseek($this->resource, $pos);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getContentAsResource()
    {
        if (!rewind($this->resource)) {
            throw new RuntimeException('Failed to rewind the stream');
        }

        return $this->resource;
    }
}
