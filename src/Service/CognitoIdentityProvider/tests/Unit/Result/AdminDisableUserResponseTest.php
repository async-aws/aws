<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Result;

use AsyncAws\CognitoIdentityProvider\Result\AdminDisableUserResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class AdminDisableUserResponseTest extends TestCase
{
    public function testAdminDisableUserResponse(): void
    {
        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminDisableUser.html
        $response = new SimpleMockedResponse('{}');

        $client = new MockHttpClient($response);
        $result = new AdminDisableUserResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));
        self::assertTrue($result->resolve());
    }
}
