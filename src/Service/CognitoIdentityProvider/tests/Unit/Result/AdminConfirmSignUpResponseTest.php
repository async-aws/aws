<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Result;

use AsyncAws\CognitoIdentityProvider\Result\AdminConfirmSignUpResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class AdminConfirmSignUpResponseTest extends TestCase
{
    public function testAdminConfirmSignUpResponse(): void
    {
        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminConfirmSignUp.html
        $response = new SimpleMockedResponse('{
        }');

        $client = new MockHttpClient($response);
        $result = new AdminConfirmSignUpResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));
        self::assertTrue($result->resolve());
    }
}
