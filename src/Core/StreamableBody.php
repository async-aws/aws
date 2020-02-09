<?php

declare(strict_types=1);

namespace AsyncAws\Core;

use Symfony\Contracts\HttpClient\ResponseStreamInterface;

/**
 * Stream a response body.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class StreamableBody implements StreamableBodyInterface
{
    /**
     * @var ResponseStreamInterface
     */
    private $responseStream;

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
    public function getChunks(): ResponseStreamInterface
    {
        return $this->responseStream;
    }

    /**
     * {@inheritdoc}
     */
    public function getContentAsString(): string
    {
        $resource = $this->getContentAsResource();

        try {
            return \stream_get_contents($resource);
        } finally {
            \fclose($resource);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getContentAsResource()
    {
        $resource = \fopen('php://temp', 'rw+');

        try {
            foreach ($this->responseStream as $chunk) {
                fwrite($resource, $chunk->getContent());
            }

            // Rewind
            \fseek($resource, 0, \SEEK_SET);

            return $resource;
        } catch (\Throwable $e) {
            \fclose($resource);

            throw $e;
        }
    }
}
