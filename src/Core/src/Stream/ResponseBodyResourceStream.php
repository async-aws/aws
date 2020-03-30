<?php

declare(strict_types=1);

namespace AsyncAws\Core\Stream;

use AsyncAws\Core\Exception\RuntimeException;

/**
 * Stream a resource body.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class ResponseBodyResourceStream implements ResultStream
{
    /**
     * @var resource
     */
    private $resource;

    public function __construct($resource)
    {
        $this->resource = $resource;
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
        $inPos = 0;
        while (true) {
            // move the cursor to reading position and remember the original position (it may changes)
            $outPos = \ftell($this->resource);
            if ($outPos !== $inPos && 0 !== \fseek($this->resource, $inPos)) {
                throw new RuntimeException('The stream is not seekable');
            }

            $content = \fread($this->resource, 64 * 1024);
            // break here as `feof` always returns false after calling `ftell`
            if (\feof($this->resource)) {
                if (\fseek($this->resource, $outPos)) {
                    throw new RuntimeException('The stream is not seekable');
                }
                yield $content;

                break;
            }

            // move the cursor to original position and remember the reading position
            $inPos = \ftell($this->resource);
            if ($outPos !== $inPos && 0 !== \fseek($this->resource, $outPos)) {
                throw new RuntimeException('The stream is not seekable');
            }

            yield $content;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getContentAsString(): string
    {
        $pos = \ftell($this->resource);

        try {
            if (!\rewind($this->resource)) {
                throw new RuntimeException('Failed to rewind the stream');
            }

            return \stream_get_contents($this->resource);
        } finally {
            \fseek($this->resource, $pos);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getContentAsResource()
    {
        if (!\rewind($this->resource)) {
            throw new RuntimeException('Failed to rewind the stream');
        }

        return $this->resource;
    }
}
