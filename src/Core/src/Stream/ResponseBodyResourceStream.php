<?php

declare(strict_types=1);

namespace AsyncAws\Core\Stream;

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
    private $responseStream;

    public function __construct($responseStream)
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
        $pos = \ftell($this->responseStream);

        try {
            if (!\rewind($this->responseStream)) {
                throw new \InvalidArgumentException('Failed to rewind the stream');
            }

            while (!\feof($this->responseStream)) {
                yield \fread($this->responseStream, 64 * 1024);
            }
        } finally {
            \fseek($this->responseStream, $pos);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getContentAsString(): string
    {
        $pos = \ftell($this->responseStream);

        try {
            if (!\rewind($this->responseStream)) {
                throw new \InvalidArgumentException('Failed to rewind the stream');
            }

            return \stream_get_contents($this->responseStream);
        } finally {
            \fseek($this->responseStream, $pos);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getContentAsResource()
    {
        return $this->responseStream;
    }
}
