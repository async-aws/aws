<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\Stream;

use AsyncAws\Core\Stream\ResourceStream;
use PHPUnit\Framework\TestCase;

class ResourceStreamTest extends TestCase
{
    /**
     * @dataProvider provideLengths
     */
    public function testLength($content, ?int $expected): void
    {
        $stream = ResourceStream::create($content);

        self::assertSame($expected, $stream->length());
    }

    /**
     * @dataProvider provideStrings
     */
    public function testStringify($content, string $expected): void
    {
        $stream = ResourceStream::create($content);

        self::assertSame($expected, $stream->stringify());
    }

    /**
     * @dataProvider provideChunks
     */
    public function testChunk($content, int $size, array $expected): void
    {
        $stream = ResourceStream::create($content, $size);
        self::assertSame($expected, iterator_to_array($stream));
    }

    public function provideLengths(): iterable
    {
        $resource = fopen(__DIR__ . '/../../../LICENSE', 'r');
        yield [$resource, 1099];

        $resource = fopen('php://temp', 'rw+');
        fwrite($resource, 'Hello World');
        yield [$resource, 11];

        $resource = fopen('php://temp', 'rw+');
        fwrite($resource, 'Hello World');
        fseek($resource, 5);
        yield [$resource, 11];
    }

    public function provideStrings(): iterable
    {
        $resource = fopen('php://temp', 'rw+');
        fwrite($resource, 'Hello World');
        yield [$resource, 'Hello World'];

        $resource = fopen('php://temp', 'rw+');
        fwrite($resource, 'Hello World');
        fseek($resource, 5);
        yield [$resource, 'Hello World'];
    }

    public function provideChunks(): iterable
    {
        $resource = fopen('php://temp', 'rw+');
        fwrite($resource, 'Hello World');
        yield [$resource, 3, ['Hel', 'lo ', 'Wor', 'ld']];
    }
}
