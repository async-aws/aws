<?php

namespace AsyncAws\Core\Test;

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
