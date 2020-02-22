<?php

namespace AsyncAws\Core\Tests\Unit\Input;

use AsyncAws\Core\Sts\Input\GetCallerIdentityRequest;
use PHPUnit\Framework\TestCase;

class GetCallerIdentityRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new GetCallerIdentityRequest([

        ]);

        $expected = trim('
        Action=GetCallerIdentity
        &Version=2011-06-15
        &ChangeIt=Change+it
                        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
