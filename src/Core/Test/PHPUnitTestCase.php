<?php

namespace AsyncAws\Core\Test;

use PHPUnit\Framework\TestCase;

/*
 *  Because AsyncAws use symfony/phpunit-bridge and don't requires phpunit/phpunit,
 *  this class may not exits but is required by the generator and static analyzer tools
 */
if (\class_exists(TestCase::class)) {
    class PHPUnitTestCase extends TestCase
    {
    }
} else {
    class PHPUnitTestCase
    {
        public static function assertEqualsCanonicalizing($expected, $actual, string $message = ''): void
        {
        }
    }
}

