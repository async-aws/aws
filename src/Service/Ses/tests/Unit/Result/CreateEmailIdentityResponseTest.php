<?php

namespace AsyncAws\Ses\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ses\Result\CreateEmailIdentityResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateEmailIdentityResponseTest extends TestCase
{
    public function testCreateEmailIdentityResponse(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_CreateEmailIdentity.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new CreateEmailIdentityResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('changeIt', $result->getIdentityType());
        self::assertFalse($result->getVerifiedForSendingStatus());
        // self::assertTODO(expected, $result->getDkimAttributes());
    }
}
