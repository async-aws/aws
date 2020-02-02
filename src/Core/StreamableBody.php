<?php

declare(strict_types=1);

namespace AsyncAws\Core;

use Symfony\Contracts\HttpClient\ResponseStreamInterface;

/**
 * Stream a response body.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class StreamableBody
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
     *  $fileHandler = fopen('/output.pdf', 'w');
     *  foreach ($result->getBody()->getChunks() as $chunk) {
     *     fwrite($fileHandler, $chunk->getContent());
     *  }.
     */
    public function getChunks(): ResponseStreamInterface
    {
        return $this->responseStream;
    }

    public function getContentAsString(): string
    {
        $resource = \fopen('php://temp', 'rw+');

        try {
            foreach ($this->responseStream as $chunk) {
                fwrite($resource, $chunk->getContent());
            }

            // Rewind
            \fseek($resource, 0, \SEEK_SET);

            return \stream_get_contents($resource);
        } finally {
            fclose($resource);
        }
    }
}
