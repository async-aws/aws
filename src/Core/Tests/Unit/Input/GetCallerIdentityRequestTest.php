<?php

namespace AsyncAws\Core\Tests\Unit\Input;

use AsyncAws\Core\Sts\Input\GetCallerIdentityRequest;
use PHPUnit\Framework\TestCase;

class GetCallerIdentityRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        $input = new GetCallerIdentityRequest();

        /** @see https://docs.aws.amazon.com/STS/latest/APIReference/API_GetCallerIdentity.html */
        $expected = trim('
Action=GetCallerIdentity
&Version=2011-06-15
        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
