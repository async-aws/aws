<?php

namespace AsyncAws\Iam\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iam\Input\DeleteUserPolicyRequest;

class DeleteUserPolicyRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteUserPolicyRequest([
            'UserName' => 'Juan',
            'PolicyName' => 'ExamplePolicy',
        ]);

        // see example-1.json from SDK
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=DeleteUserPolicy
            &PolicyName=ExamplePolicy
            &UserName=Juan
            &Version=2010-05-08
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
