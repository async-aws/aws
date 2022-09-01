<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Result;

use AsyncAws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use AsyncAws\CognitoIdentityProvider\Input\ListGroupsRequest;
use AsyncAws\CognitoIdentityProvider\Result\ListGroupsResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListGroupsResponseTest extends TestCase
{
    public function testListGroupsResponse(): void
    {
        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_ListGroups.html
        $response = new SimpleMockedResponse(
            json_encode([
                'Groups' => [
                    [
                        'GroupName' => 'group1',
                        'UserPoolId' => 'eu-central-1_OFJfNJQwx',
                        'RoleArn' => 'arn:aws:iam::4224242:role/allow-cognito-user-pool',
                        'LastModifiedDate' => '2022-08-11T16:32:26.116000+03:00',
                        'CreationDate' => '2022-08-11T16:32:26.116000+03:00',
                    ],
                ],
                'NextToken' => '5cf02366-fb0c-449e-aa2d-142c6aac49e0',
            ])
        );

        $client = new MockHttpClient($response);
        $result = new ListGroupsResponse(
            new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()),
            new CognitoIdentityProviderClient(),
            new ListGroupsRequest([
                'UserPoolId' => 'eu-central-1_OFJfNJQwx',
            ])
        );

        self::assertIsIterable($result->getGroups());
        self::assertSame('5cf02366-fb0c-449e-aa2d-142c6aac49e0', $result->getNextToken());
    }
}
