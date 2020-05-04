<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Result;

use AsyncAws\CognitoIdentityProvider\Result\SetUserMFAPreferenceResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class SetUserMFAPreferenceResponseTest extends TestCase
{
    public function testSetUserMFAPreferenceResponse(): void
    {
        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminSetUserMFAPreference.html

        $response = new SimpleMockedResponse('');

        $client = new MockHttpClient($response);
        $result = new SetUserMFAPreferenceResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(200, $result->info()['response']->getStatusCode());
        self::assertSame('', $result->info()['response']->getContent());
    }
}
