<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\Stream;

use AsyncAws\Core\Exception\LogicException;
use AsyncAws\Core\Exception\RuntimeException;
use AsyncAws\Core\Stream\ReadOnceResultStream;
use AsyncAws\Core\Stream\ResponseBodyNonBufferedStream;
use PHPUnit\Framework\TestCase;

class ResponseBodyNonBufferedStreamTest extends TestCase
{
    public function testItReturnsOriginalResource(): void
    {
        $resource = self::createResource('abcdef');
        $stream = new ResponseBodyNonBufferedStream($resource);

        self::assertSame($resource, $stream->getContentAsResource());
    }

    public function testItIsMarkedAsReadOnce(): void
    {
        $stream = new ResponseBodyNonBufferedStream(self::createResource('abcdef'));

        self::assertInstanceOf(ReadOnceResultStream::class, $stream);
    }

    public function testItReadsStringFromCurrentPosition(): void
    {
        $resource = self::createResource('abcdef');
        fseek($resource, 2);
        $stream = new ResponseBodyNonBufferedStream($resource);

        self::assertSame('cdef', $stream->getContentAsString());
    }

    public function testItReadsChunksWithoutRewinding(): void
    {
        $resource = self::createResource('abcdef');
        fseek($resource, 2);
        $stream = new ResponseBodyNonBufferedStream($resource);

        self::assertSame(['cdef'], iterator_to_array($stream->getChunks()));
    }

    public function testItCanOnlyBeConsumedOnce(): void
    {
        $stream = new ResponseBodyNonBufferedStream(self::createResource('abcdef'));

        $stream->getContentAsResource();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('The non-buffered response stream has already been consumed.');

        $stream->getContentAsString();
    }

    public function testGetChunksMarksStreamConsumedAtCallTime(): void
    {
        $stream = new ResponseBodyNonBufferedStream(self::createResource('abcdef'));

        $chunks = $stream->getChunks();

        $this->expectException(LogicException::class);
        $stream->getChunks();

        iterator_to_array($chunks);
    }

    public function testInvalidResourceIsRejected(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The response stream is not a valid resource.');

        new ResponseBodyNonBufferedStream('not-a-resource');
    }

    /**
     * @return resource
     */
    private static function createResource(string $content)
    {
        $resource = fopen('php://temp', 'rb+');
        self::assertIsResource($resource);
        fwrite($resource, $content);
        rewind($resource);

        return $resource;
    }
}
