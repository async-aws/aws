<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Result;

use AsyncAws\CognitoIdentityProvider\Result\ConfirmSignUpResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ConfirmSignUpResponseTest extends TestCase
{
    public function testConfirmSignUpResponse(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/cognitoidentityprovider/latest/APIReference/API_ConfirmSignUp.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new ConfirmSignUpResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));
    }
}
