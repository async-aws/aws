<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\Stream;

use AsyncAws\Core\Stream\CallableStream;
use AsyncAws\Core\Stream\IterableStream;
use AsyncAws\Core\Stream\RequestStream;
use AsyncAws\Core\Stream\RewindableStream;
use AsyncAws\Core\Stream\StringStream;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RewindableStreamTest extends TestCase
{
    #[DataProvider('provideLengths')]
    public function testLength(RequestStream $content, ?int $expected): void
    {
        $stream = RewindableStream::create($content);

        self::assertSame($expected, $stream->length());
    }

    #[DataProvider('provideStrings')]
    public function testStringify(RequestStream $content, string $expected): void
    {
        $stream = RewindableStream::create($content);

        self::assertSame($expected, $stream->stringify());
    }

    #[DataProvider('provideChunks')]
    public function testChunk(RequestStream $content, array $expected): void
    {
        $stream = RewindableStream::create($content);

        self::assertSame($expected, iterator_to_array($stream));
    }

    /**
     * Asserts the streams returns always the same value, but calls the closure once.
     */
    public static function testNoRewind(): void
    {
        $count = 0;
        $stream = RewindableStream::create(CallableStream::create(function () use (&$count) {
            ++$count;

            return 1 === $count ? 'foo' : '';
        }));

        self::assertSame('foo', $stream->stringify());
        self::assertSame('foo', $stream->stringify());
        self::assertSame(2, $count);
    }

    public static function provideLengths(): iterable
    {
        yield [StringStream::create('Hello world'), 11];
        yield [CallableStream::create(function () {}), null];
    }

    public static function provideStrings(): iterable
    {
        yield [StringStream::create('Hello world'), 'Hello world'];
        yield [IterableStream::create((function () { yield 'Hello world'; })()), 'Hello world'];
    }

    public static function provideChunks(): iterable
    {
        yield [StringStream::create('Hello world'), ['Hello world']];
    }
}
