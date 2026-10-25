<?php

declare(strict_types=1);

namespace AsyncAws\Core\Stream;

use AsyncAws\Core\Exception\LogicException;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

/**
 * Stream that reads an HTTP response body once, as it arrives, without buffering it.
 */
final class ResponseBodyNonBufferedStream implements ReadOnceResultStream, ResultStream
{
    /**
     * @var ResponseStreamInterface
     */
    private $responseStream;

    /**
     * @var bool
     */
    private $consumed = false;

    public function __construct(ResponseStreamInterface $responseStream)
    {
        $this->responseStream = $responseStream;
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

        return $this->readChunks();
    }

    /**
     * {@inheritdoc}
     */
    public function getContentAsString(): string
    {
        $this->markConsumed();

        $content = '';
        foreach ($this->readChunks() as $chunk) {
            $content .= $chunk;
        }

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function getContentAsResource()
    {
        $this->markConsumed();

        return IteratorStreamWrapper::createResource($this->readChunks());
    }

    /**
     * @return \Generator<string>
     */
    private function readChunks(): \Generator
    {
        foreach ($this->responseStream as $chunk) {
            $content = $chunk->getContent();
            if ('' !== $content) {
                yield $content;
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
