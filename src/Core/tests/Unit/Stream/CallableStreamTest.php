<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\Stream;

use AsyncAws\Core\Stream\CallableStream;
use PHPUnit\Framework\TestCase;

class CallableStreamTest extends TestCase
{
    /**
     * @dataProvider provideLengths
     */
    public function testLength(callable $content, ?int $expected): void
    {
        $stream = CallableStream::create($content);

        self::assertSame($expected, $stream->length());
    }

    /**
     * @dataProvider provideStrings
     */
    public function testStringify(callable $content, string $expected): void
    {
        $stream = CallableStream::create($content);

        self::assertSame($expected, $stream->stringify());
    }

    /**
     * @dataProvider provideChunks
     */
    public function testChunk(callable $content, array $expected): void
    {
        $stream = CallableStream::create($content);

        self::assertSame($expected, \iterator_to_array($stream));
    }

    public function provideLengths(): iterable
    {
        yield [(function () {
            return 'Hello world';
        }), null];
    }

    public function provideStrings(): iterable
    {
        $f = static function (string ...$items) {
            return static function () use (&$items) {
                return \array_shift($items) ?? '';
            };
        };
        yield [$f('Hello world'), 'Hello world'];
        yield [$f('Hello', ' ', 'world'), 'Hello world'];
        yield [$f('Hello', ' ', '', 'world'), 'Hello '];
    }

    public function provideChunks(): iterable
    {
        $f = static function (string ...$items) {
            return static function () use (&$items) {
                return \array_shift($items) ?? '';
            };
        };
        yield [$f('Hello world'), ['Hello world']];
        yield [$f('Hello', ' ', 'world'), ['Hello', ' ', 'world']];
        yield [$f('Hello', ' ', '', 'world'), ['Hello', ' ']];
    }
}
