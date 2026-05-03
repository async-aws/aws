<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\RequestContext;
use PHPUnit\Framework\TestCase;

class RequestContextTest extends TestCase
{
    public function testResponseBufferDefaultsToTrue(): void
    {
        $context = new RequestContext();

        self::assertTrue($context->shouldBufferResponse());
    }

    public function testResponseBufferCanBeDisabled(): void
    {
        $context = new RequestContext(['responseBuffer' => false]);

        self::assertFalse($context->shouldBufferResponse());
    }

    public function testUnknownOptionsAreRejected(): void
    {
        $this->expectException(InvalidArgument::class);
        $this->expectExceptionMessage('Invalid option(s) "foo" passed to "AsyncAws\Core\RequestContext::__construct".');

        new RequestContext(['foo' => true]);
    }
}
