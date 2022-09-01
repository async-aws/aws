<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\CreateGroupRequest;
use AsyncAws\Core\Test\TestCase;

class CreateGroupRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateGroupRequest([
            'GroupName' => 'testGroupName',
            'UserPoolId' => 'us-east-test',
            'Description' => 'Some very important group',
            'RoleArn' => 'arn:aws:iam::4224242:role/test',
            'Precedence' => 42,
        ]);

        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_CreateGroup.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-Amz-Target: AWSCognitoIdentityProviderService.CreateGroup

            {
              "GroupName": "testGroupName",
              "UserPoolId": "us-east-test",
              "Description": "Some very important group",
              "RoleArn": "arn:aws:iam::4224242:role/test",
              "Precedence": 42
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
