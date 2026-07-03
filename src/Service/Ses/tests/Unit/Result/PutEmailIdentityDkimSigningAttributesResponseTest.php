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
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_PutEmailIdentityDkimSigningAttributes.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new PutEmailIdentityDkimSigningAttributesResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('changeIt', $result->getDkimStatus());
        // self::assertTODO(expected, $result->getDkimTokens());
        self::assertSame('changeIt', $result->getSigningHostedZone());
    }
}
