<?php

declare(strict_types=1);

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Lambda\Input\InvocationRequest;
use PHPUnit\Framework\TestCase;

class InvocationRequestTest extends TestCase
{
    public function testFunctionName()
    {
        $input = InvocationRequest::create(['FunctionName' => 'foobar']);
        $uri = $input->requestUri();

        self::assertStringContainsString('foobar', $uri);
    }
}
