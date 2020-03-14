<?php

namespace AsyncAws\Core\Tests\Unit\Input;

use AsyncAws\Core\Sts\Input\GetCallerIdentityRequest;
use AsyncAws\Core\Test\TestCase;

class GetCallerIdentityRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetCallerIdentityRequest();

        /** @see https://docs.aws.amazon.com/STS/latest/APIReference/API_GetCallerIdentity.html */
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=GetCallerIdentity
            &Version=2011-06-15
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
