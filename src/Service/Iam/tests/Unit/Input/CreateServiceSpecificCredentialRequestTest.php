<?php

namespace AsyncAws\Iam\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iam\Input\CreateServiceSpecificCredentialRequest;

class CreateServiceSpecificCredentialRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateServiceSpecificCredentialRequest([
            'UserName' => 'test@async-aws.com',
            'ServiceName' => 'codecommit.amazonaws.com',
        ]);

        // see https://docs.aws.amazon.com/IAM/latest/APIReference/API_CreateServiceSpecificCredential.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=CreateServiceSpecificCredential
            &ServiceName=codecommit.amazonaws.com
            &UserName=test%40async-aws.com
            &Version=2010-05-08
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
