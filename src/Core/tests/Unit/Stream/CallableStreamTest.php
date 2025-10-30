<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\Stream;

use AsyncAws\Core\Stream\CallableStream;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CallableStreamTest extends TestCase
{
    #[DataProvider('provideLengths')]
    public function testLength(callable $content, ?int $expected): void
    {
        $stream = CallableStream::create($content);

        self::assertSame($expected, $stream->length());
    }

    #[DataProvider('provideStrings')]
    public function testStringify(callable $content, string $expected): void
    {
        $stream = CallableStream::create($content);

        self::assertSame($expected, $stream->stringify());
    }

    #[DataProvider('provideChunks')]
    public function testChunk(callable $content, array $expected): void
    {
        $stream = CallableStream::create($content);

        self::assertSame($expected, iterator_to_array($stream));
    }

    public static function provideLengths(): iterable
    {
        yield [function () {
            return 'Hello world';
        }, null];
    }

    public static function provideStrings(): iterable
    {
        $f = static function (string ...$items) {
            return static function () use (&$items) {
                return array_shift($items) ?? '';
            };
        };
        yield [$f('Hello world'), 'Hello world'];
        yield [$f('Hello', ' ', 'world'), 'Hello world'];
        yield [$f('Hello', ' ', '', 'world'), 'Hello '];
    }

    public static function provideChunks(): iterable
    {
        $f = static function (string ...$items) {
            return static function () use (&$items) {
                return array_shift($items) ?? '';
            };
        };
        yield [$f('Hello world'), ['Hello world']];
        yield [$f('Hello', ' ', 'world'), ['Hello', ' ', 'world']];
        yield [$f('Hello', ' ', '', 'world'), ['Hello', ' ']];
    }
}
