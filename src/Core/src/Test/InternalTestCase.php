<?php

namespace AsyncAws\Core\Test;

/**
 * Because AsyncAws use symfony/phpunit-bridge and doesn't require phpunit/phpunit,
 * this class may not exist but is required by the generator and static analyzer tools.
 *
 * @internal use AsyncAws\Core\Test\TestCase instead
 */
class InternalTestCase
{
    /**
     * @param mixed $expected
     * @param mixed $actual
     */
    public static function assertEqualsCanonicalizing($expected, $actual, string $message = ''): void
    {
    }

    /**
     * @param mixed $expected
     * @param mixed $actual
     */
    public static function assertEqualsIgnoringCase($expected, $actual, string $message = ''): void
    {
    }

    /**
     * @param mixed $expected
     * @param mixed $actual
     */
    public static function assertSame($expected, $actual, string $message = ''): void
    {
    }

    public static function assertJsonStringEqualsJsonString(string $expected, string $actual, string $message = ''): void
    {
    }

    /**
     * @param \DOMDocument|string $expected
     * @param \DOMDocument|string $actual
     */
    public static function assertXmlStringEqualsXmlString($expected, $actual, string $message = ''): void
    {
    }
}
