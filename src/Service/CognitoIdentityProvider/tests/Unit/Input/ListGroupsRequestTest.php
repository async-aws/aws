<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\ListGroupsRequest;
use AsyncAws\Core\Test\TestCase;

class ListGroupsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListGroupsRequest([
            'UserPoolId' => 'us-east-1_1337oL33t',
            'Limit' => 42,
            'NextToken' => '5cf02366-fb0c-449e-aa2d-142c6aac49e0',
        ]);

        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_ListGroups.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-Amz-Target: AWSCognitoIdentityProviderService.ListGroups

            {
               "UserPoolId": "us-east-1_1337oL33t",
               "Limit": 42,
               "NextToken": "5cf02366-fb0c-449e-aa2d-142c6aac49e0"
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
