<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\Stream;

use AsyncAws\Core\Stream\IterableStream;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class IterableStreamTest extends TestCase
{
    #[DataProvider('provideLengths')]
    public function testLength(iterable $content, ?int $expected): void
    {
        $stream = IterableStream::create($content);

        self::assertSame($expected, $stream->length());
    }

    #[DataProvider('provideStrings')]
    public function testStringify(iterable $content, string $expected): void
    {
        $stream = IterableStream::create($content);

        self::assertSame($expected, $stream->stringify());
    }

    #[DataProvider('provideChunks')]
    public function testChunk(iterable $content, array $expected): void
    {
        $stream = IterableStream::create($content);

        self::assertSame($expected, iterator_to_array($stream));
    }

    public static function provideLengths(): iterable
    {
        yield [(function () {
            yield 'Hello world';
        })(), null];
    }

    public static function provideStrings(): iterable
    {
        yield [(function () {
            yield 'Hello world';
        })(), 'Hello world'];
        yield [(function () {
            yield 'Hello';
            yield ' ';
            yield 'world';
        })(), 'Hello world'];
        yield [(function () {
            yield 'Hello';
            yield '';
            yield ' ';
            yield '';
            yield 'world';
        })(), 'Hello world'];
    }

    public static function provideChunks(): iterable
    {
        yield [(function () {
            yield 'Hello world';
        })(), ['Hello world']];
        yield [(function () {
            yield 'Hello';
            yield ' ';
            yield 'world';
        })(), ['Hello', ' ', 'world']];
        yield [(function () {
            yield 'Hello';
            yield '';
            yield ' ';
            yield '';
            yield 'world';
        })(), ['Hello', '', ' ', '', 'world']];
    }
}
