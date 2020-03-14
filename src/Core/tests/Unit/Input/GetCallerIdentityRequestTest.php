<?php

namespace AsyncAws\Core\Tests\Unit\Input;

use AsyncAws\Core\Sts\Input\GetCallerIdentityRequest;
use AsyncAws\Core\Test\TestCase;

class GetCallerIdentityRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        $input = new GetCallerIdentityRequest();

        /** @see https://docs.aws.amazon.com/STS/latest/APIReference/API_GetCallerIdentity.html */
        $expected = '
Action=GetCallerIdentity
&Version=2011-06-15
        ';

        self::assertHttpFormEqualsHttpForm($expected, $input->request()->getBody()->stringify());
    }
}
