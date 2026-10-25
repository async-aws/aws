<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\Stream;

use AsyncAws\Core\Exception\LogicException;
use AsyncAws\Core\Stream\ResponseBodyNonBufferedStream;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Chunk\DataChunk;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpClient\Response\ResponseStream;

class ResponseBodyNonBufferedStreamTest extends TestCase
{
    public function testToString(): void
    {
        $stream = $this->createStream(['abc', 'def']);

        self::assertSame('abcdef', (string) $stream);
    }

    public function testGetChunksYieldsTheBodyAsItArrives(): void
    {
        $stream = $this->createStream(['abc', 'def']);

        self::assertSame(['abc', 'def'], iterator_to_array($stream->getChunks(), false));
    }

    public function testGetChunksThrowsOnTransportErrors(): void
    {
        $stream = $this->createStream($this->bodyResetAfter('abc'));

        $this->expectException(TransportException::class);
        $this->expectExceptionMessage('Connection reset.');

        iterator_to_array($stream->getChunks(), false);
    }

    public function testGetChunksMarksStreamConsumedAtCallTime(): void
    {
        $stream = $this->createStream(['abc']);

        $stream->getChunks();

        $this->expectException(LogicException::class);
        $stream->getChunks();
    }

    public function testGetContentAsString(): void
    {
        $stream = $this->createStream(['abc', 'def']);

        self::assertSame('abcdef', $stream->getContentAsString());
    }

    public function testGetContentAsStringThrowsOnTransportErrors(): void
    {
        $stream = $this->createStream($this->bodyResetAfter('abc'));

        $this->expectException(TransportException::class);
        $this->expectExceptionMessage('Connection reset.');

        $stream->getContentAsString();
    }

    public function testGetContentAsResource(): void
    {
        $resource = $this->createStream(['abc', str_repeat('d', 10000)])->getContentAsResource();

        self::assertSame('abc' . str_repeat('d', 10000), stream_get_contents($resource));
        self::assertTrue(feof($resource));
        self::assertFalse(stream_get_meta_data($resource)['seekable']);
        self::assertFalse(fstat($resource));
    }

    public function testGetContentAsResourcePullsChunksOnlyAsItIsRead(): void
    {
        $pulled = 0;
        $chunks = (static function () use (&$pulled): \Generator {
            $response = new MockResponse();
            foreach (['abc', 'def', 'ghi'] as $i => $content) {
                ++$pulled;
                yield $response => new DataChunk($i * 3, $content);
            }
        })();
        $stream = new ResponseBodyNonBufferedStream(new ResponseStream($chunks));

        $resource = $stream->getContentAsResource();

        self::assertFalse(feof($resource));
        self::assertSame(0, $pulled);
        self::assertSame('abc', fread($resource, 3));
        self::assertSame(1, $pulled);
        self::assertFalse(feof($resource));
        self::assertSame('def', fread($resource, 3));
        self::assertSame(2, $pulled);
    }

    public function testGetContentAsResourceThrowsOnTransportErrors(): void
    {
        $resource = $this->createStream($this->bodyResetAfter('abc'))->getContentAsResource();

        self::assertSame('abc', fread($resource, 8192));

        $this->expectException(TransportException::class);
        $this->expectExceptionMessage('Connection reset.');

        fread($resource, 8192);
    }

    public function testItCanOnlyBeConsumedOnce(): void
    {
        $stream = $this->createStream(['abc']);

        $stream->getContentAsResource();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('The non-buffered response stream has already been consumed.');

        $stream->getContentAsString();
    }

    private function createStream(iterable $body): ResponseBodyNonBufferedStream
    {
        $client = new MockHttpClient(new MockResponse($body));

        return new ResponseBodyNonBufferedStream($client->stream($client->request('GET', 'http://localhost', ['buffer' => false])));
    }

    private function bodyResetAfter(string $chunk): \Generator
    {
        yield $chunk;

        throw new \RuntimeException('Connection reset.');
    }
}
