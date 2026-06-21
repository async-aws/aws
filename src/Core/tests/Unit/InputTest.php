<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StringStream;
use PHPUnit\Framework\TestCase;

class InputTest extends TestCase
{
    public function testResponseBufferDefaultsToTrue(): void
    {
        $input = new TestInput();

        self::assertTrue($input->shouldBufferResponse());
    }

    public function testResponseBufferCanBeDisabled(): void
    {
        $input = new TestInput(['@responseBuffer' => false]);

        self::assertFalse($input->shouldBufferResponse());
    }

    public function testRegionBehaviorIsUnchanged(): void
    {
        $input = new TestInput(['@region' => 'eu-west-1']);

        self::assertSame('eu-west-1', $input->getRegion());
    }
}

class TestInput extends Input
{
    public function __construct(array $input = [])
    {
        parent::__construct($input);
    }

    public function request(): Request
    {
        return new Request('GET', '/', [], [], StringStream::create(''));
    }
}
