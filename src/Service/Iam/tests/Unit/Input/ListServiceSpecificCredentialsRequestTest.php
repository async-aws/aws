<?php

namespace AsyncAws\Iam\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iam\Input\ListServiceSpecificCredentialsRequest;

class ListServiceSpecificCredentialsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListServiceSpecificCredentialsRequest([
            'UserName' => 'test@async-aws.com',
            'ServiceName' => 'codecommit.amazonaws.com',
        ]);

        // see https://docs.aws.amazon.com/IAM/latest/APIReference/API_ListServiceSpecificCredentials.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=ListServiceSpecificCredentials
            &ServiceName=codecommit.amazonaws.com
            &UserName=test%40async-aws.com
            &Version=2010-05-08
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
