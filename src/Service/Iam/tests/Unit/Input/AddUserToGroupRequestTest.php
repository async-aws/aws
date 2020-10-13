<?php

namespace AsyncAws\Iam\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iam\Input\AddUserToGroupRequest;

class AddUserToGroupRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new AddUserToGroupRequest([
            'GroupName' => 'change me',
            'UserName' => 'change me',
        ]);

        // see example-1.json from SDK
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            GroupName=Admins&UserName=Bob
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
