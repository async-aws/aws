<?php

namespace AsyncAws\Core\Test;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    /**
     * Asserts that two Body documents are equal.
     */
    public static function assertHttpFormEqualsHttpForm(string $expected, string $actual, string $message = '')
    {
        $expectedArray = \preg_split('/[\n&\s]+/', trim($expected));
        $actualArray = \preg_split('/[\n&\s]+/', trim($actual));

        self::assertEqualsCanonicalizing($expectedArray, $actualArray, $message);
    }
}

// Because AsyncAws use symfony/phpunit-bridge and don't requires phpunit/phpunit, this class may not exits but is required by the generator and static analyzer tools
if (!\class_exists(PHPUnitTestCase::class)) {
    class DummyTestCase
    {
        public static function assertEqualsCanonicalizing($expected, $actual, string $message = ''): void
        {
        }
    }
    \class_alias(DummyTestCase::class, PHPUnitTestCase::class);
}
