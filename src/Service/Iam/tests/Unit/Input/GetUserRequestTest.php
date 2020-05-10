<?php

namespace AsyncAws\Iam\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iam\Input\GetUserRequest;

class GetUserRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetUserRequest([
            'UserName' => 'Bob',
        ]);

        // see example-1.json from SDK
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=GetUser
            &Version=2010-05-08
            &UserName=Bob
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
