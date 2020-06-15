<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Result;

use AsyncAws\CognitoIdentityProvider\Result\ConfirmForgotPasswordResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ConfirmForgotPasswordResponseTest extends TestCase
{
    public function testConfirmForgotPasswordResponse(): void
    {
        // see https://docs.aws.amazon.com/cognitoidentityprovider/latest/APIReference/API_ConfirmForgotPassword.html
        $response = new SimpleMockedResponse('', [], 200);

        $client = new MockHttpClient($response);
        $result = new ConfirmForgotPasswordResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertTrue(true);
    }
}
