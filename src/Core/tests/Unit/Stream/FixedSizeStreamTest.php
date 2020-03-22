<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\Stream;

use AsyncAws\Core\Stream\CallableStream;
use AsyncAws\Core\Stream\FixedSizeStream;
use AsyncAws\Core\Stream\IterableStream;
use AsyncAws\Core\Stream\RequestStream;
use AsyncAws\Core\Stream\StringStream;
use PHPUnit\Framework\TestCase;

class FixedSizeStreamTest extends TestCase
{
    /**
     * @dataProvider provideLengths
     */
    public function testLength(RequestStream $content, ?int $expected): void
    {
        $stream = FixedSizeStream::create($content);

        self::assertSame($expected, $stream->length());
    }

    /**
     * @dataProvider provideStrings
     */
    public function testStringify(RequestStream $content, string $expected): void
    {
        $stream = FixedSizeStream::create($content);

        self::assertSame($expected, $stream->stringify());
    }

    /**
     * @dataProvider provideChunks
     */
    public function testChunk(RequestStream $content, int $size, array $expected): void
    {
        $stream = FixedSizeStream::create($content, $size);

        self::assertSame($expected, \iterator_to_array($stream));
    }

    public function provideLengths(): iterable
    {
        yield [StringStream::create('Hello world'), 11];
        yield [CallableStream::create(function () {}), null];
    }

    public function provideStrings(): iterable
    {
        yield [StringStream::create('Hello world'), 'Hello world'];
        yield [IterableStream::create((function () { yield 'Hello world'; })()), 'Hello world'];
    }

    public function provideChunks(): iterable
    {
        yield [StringStream::create('Hello world'), 3, ['Hel', 'lo ', 'wor', 'ld']];
        yield [IterableStream::create((function () {
            yield 'Hello';
            yield '';
            yield ' ';
            yield 'world';
            yield '';
        })()), 2, ['He', 'll', 'o ', 'wo', 'rl', 'd']];
    }
}
