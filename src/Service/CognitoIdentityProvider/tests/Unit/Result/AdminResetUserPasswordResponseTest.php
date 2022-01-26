<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Result;

use AsyncAws\CognitoIdentityProvider\Result\AdminResetUserPasswordResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class AdminResetUserPasswordResponseTest extends TestCase
{
    public function testAdminResetUserPasswordResponse(): void
    {
        // see https://docs.aws.amazon.com/cognitoidentityprovider/latest/APIReference/API_AdminResetUserPassword.html
        $response = new SimpleMockedResponse('{
        }');

        $client = new MockHttpClient($response);
        $result = new AdminResetUserPasswordResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));
        self::assertTrue($result->resolve());
    }
}
