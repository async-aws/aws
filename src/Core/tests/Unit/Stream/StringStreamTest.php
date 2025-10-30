<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\Stream;

use AsyncAws\Core\Stream\StringStream;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class StringStreamTest extends TestCase
{
    #[DataProvider('provideLengths')]
    public function testLength(string $content, ?int $expected): void
    {
        $stream = StringStream::create($content);

        self::assertSame($expected, $stream->length());
    }

    #[DataProvider('provideStrings')]
    public function testStringify(string $content, string $expected): void
    {
        $stream = StringStream::create($content);

        self::assertSame($expected, $stream->stringify());
    }

    #[DataProvider('provideChunks')]
    public function testChunk(string $content, array $expected): void
    {
        $stream = StringStream::create($content);

        self::assertSame($expected, iterator_to_array($stream));
    }

    public static function provideLengths(): iterable
    {
        yield ['Hello world', 11];
        yield ['H', 1];
        yield ['é', 2];
    }

    public static function provideStrings(): iterable
    {
        yield ['Hello world', 'Hello world'];
    }

    public static function provideChunks(): iterable
    {
        yield ['Hello world', ['Hello world']];
    }
}
