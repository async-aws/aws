<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Result;

use AsyncAws\CognitoIdentityProvider\Result\AdminUpdateUserAttributesResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class AdminUpdateUserAttributesResponseTest extends TestCase
{
    public function testAdminUpdateUserAttributesResponse(): void
    {
        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminUpdateUserAttributes.html
        $response = new SimpleMockedResponse('');

        $client = new MockHttpClient($response);
        $result = new AdminUpdateUserAttributesResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(200, $result->info()['response']->getStatusCode());
        self::assertSame('', $result->info()['response']->getContent());
    }
}
