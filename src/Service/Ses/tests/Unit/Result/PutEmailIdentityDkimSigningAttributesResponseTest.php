<?php

namespace AsyncAws\Ses\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ses\Result\PutEmailIdentityDkimSigningAttributesResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class PutEmailIdentityDkimSigningAttributesResponseTest extends TestCase
{
    public function testPutEmailIdentityDkimSigningAttributesResponse(): void
    {
        // see https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_PutEmailIdentityDkimSigningAttributes.html
        $response = new SimpleMockedResponse('{
            "DkimStatus": "PENDING",
            "DkimTokens": ["token1", "token2", "token3"],
            "SigningHostedZone": "us-east-1.ses.example.aws"
        }');

        $client = new MockHttpClient($response);
        $result = new PutEmailIdentityDkimSigningAttributesResponse(new Response($client->request('PUT', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('PENDING', $result->getDkimStatus());
        self::assertSame(['token1', 'token2', 'token3'], $result->getDkimTokens());
        self::assertSame('us-east-1.ses.example.aws', $result->getSigningHostedZone());
    }
}
