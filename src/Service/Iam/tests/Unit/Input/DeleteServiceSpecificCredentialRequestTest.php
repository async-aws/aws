<?php

namespace AsyncAws\Iam\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iam\Input\DeleteServiceSpecificCredentialRequest;

class DeleteServiceSpecificCredentialRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteServiceSpecificCredentialRequest([
            'UserName' => 'test@async-aws.com',
            'ServiceSpecificCredentialId' => 'ACCA12345ABCDEXAMPLE',
        ]);

        // see https://docs.aws.amazon.com/IAM/latest/APIReference/API_DeleteServiceSpecificCredential.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=DeleteServiceSpecificCredential
            &ServiceSpecificCredentialId=ACCA12345ABCDEXAMPLE
            &UserName=test%40async-aws.com
            &Version=2010-05-08
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
