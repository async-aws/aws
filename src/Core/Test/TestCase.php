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
if (!\class_exists(PHPUnitTestCase::class)) {
    \class_alias(InternalTestCase::class, PHPUnitTestCase::class);
}
