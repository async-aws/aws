<?php

namespace AsyncAws\Iam\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iam\Input\CreateUserRequest;

class CreateUserRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateUserRequest([
            'Path' => '/division_abc/subdivision_xyz',
            'UserName' => 'Bob',
        ]);

        // see example-1.json from SDK
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=CreateUser
            &Version=2010-05-08
            &UserName=Bob
            &Path=%2Fdivision_abc%2Fsubdivision_xyz
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
