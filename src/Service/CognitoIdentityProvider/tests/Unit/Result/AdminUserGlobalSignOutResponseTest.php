<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Result;

use AsyncAws\CognitoIdentityProvider\Result\AdminUserGlobalSignOutResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class AdminUserGlobalSignOutResponseTest extends TestCase
{
    public function testAdminUserGlobalSignOutResponse(): void
    {
        // see https://docs.aws.amazon.com/cognitoidentityprovider/latest/APIReference/API_AdminUserGlobalSignOut.html
        $response = new SimpleMockedResponse('{}');

        $client = new MockHttpClient($response);
        $result = new AdminUserGlobalSignOutResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));
        self::assertTrue($result->resolve());
    }
}
