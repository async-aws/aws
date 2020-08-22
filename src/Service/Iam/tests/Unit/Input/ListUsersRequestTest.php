<?php

namespace AsyncAws\Iam\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iam\Input\ListUsersRequest;

class ListUsersRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListUsersRequest([
            'PathPrefix' => '/division_abc/subdivision_xyz',
        ]);

        // see https://docs.aws.amazon.com/IAM/latest/APIReference/API_ListUsers.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=ListUsers
            &Version=2010-05-08
            &PathPrefix=%2Fdivision_abc%2Fsubdivision_xyz
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
