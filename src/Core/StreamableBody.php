<?php

declare(strict_types=1);

namespace AsyncAws\Core;

use AsyncAws\Core\Exception\LogicException;
use Symfony\Component\HttpClient\Response\CurlResponse;
use Symfony\Component\HttpClient\Response\NativeResponse;
use Symfony\Component\HttpClient\Response\ResponseStream;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Stream a response body.
 *
 * @see https://symfony.com/doc/current/components/http_client.html#streaming-responses
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class StreamableBody
{
    private $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     *  $fileHandler = fopen('/output.pdf', 'w');
     *  foreach ($body->getChunks() as $chunk) {
     *     fwrite($fileHandler, $chunk->getContent());
     *  }
     *
     * @return ResponseStream
     */
    public function getChunks(float $timeout = null)
    {
        if ($this->response instanceof CurlResponse) {
            $stream = CurlResponse::stream([$this->response], $timeout);
        } elseif ($this->response instanceof NativeResponse) {
            $stream = NativeResponse::stream([$this->response], $timeout);
        } else {
            throw new LogicException(sprintf('We cannot stream a response of type "%s".', \get_class($this->response)));
        }

        return new ResponseStream($stream);
    }

    public function getContentAsString()
    {
        return $this->response->getContent(false);
    }

    public function __toString()
    {
        return $this->getContentAsString();
    }
}
