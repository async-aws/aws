<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\Stream;

use AsyncAws\Core\Stream\IterableStream;
use PHPUnit\Framework\TestCase;

class IterableStreamTest extends TestCase
{
    /**
     * @dataProvider provideLengths
     */
    public function testLength(iterable $content, ?int $expected): void
    {
        $stream = IterableStream::create($content);

        self::assertSame($expected, $stream->length());
    }

    /**
     * @dataProvider provideStrings
     */
    public function testStringify(iterable $content, string $expected): void
    {
        $stream = IterableStream::create($content);

        self::assertSame($expected, $stream->stringify());
    }

    /**
     * @dataProvider provideChunks
     */
    public function testChunk(iterable $content, array $expected): void
    {
        $stream = IterableStream::create($content);

        self::assertSame($expected, iterator_to_array($stream));
    }

    public function provideLengths(): iterable
    {
        yield [(function () {
            yield 'Hello world';
        })(), null];
    }

    public function provideStrings(): iterable
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

    public function provideChunks(): iterable
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
