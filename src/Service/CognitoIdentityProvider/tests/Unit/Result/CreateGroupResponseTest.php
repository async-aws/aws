<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Result;

use AsyncAws\CognitoIdentityProvider\Result\CreateGroupResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateGroupResponseTest extends TestCase
{
    public function testCreateGroupResponse(): void
    {
        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_CreateGroup.html
        $response = new SimpleMockedResponse(json_encode([
            'Group' => [
                'GroupName' => 'testGroupName',
                'UserPoolId' => 'us-east-test',
                'Description' => 'Some very important group',
                'RoleArn' => 'arn:aws:iam::4224242:role/test',
                'Precedence' => 42,
            ],
        ]));

        $client = new MockHttpClient($response);
        $result = new CreateGroupResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        $group = $result->getGroup();

        self::assertEquals('testGroupName', $group->getGroupName());
        self::assertEquals('us-east-test', $group->getUserPoolId());
        self::assertEquals('Some very important group', $group->getDescription());
        self::assertEquals('arn:aws:iam::4224242:role/test', $group->getRoleArn());
        self::assertEquals(42, $group->getPrecedence());
    }
}
