<?php

namespace AsyncAws\Ses\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ses\Result\GetEmailIdentityResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetEmailIdentityResponseTest extends TestCase
{
    public function testGetEmailIdentityResponse(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_GetEmailIdentity.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new GetEmailIdentityResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('changeIt', $result->getIdentityType());
        self::assertFalse($result->getFeedbackForwardingStatus());
        self::assertFalse($result->getVerifiedForSendingStatus());
        // self::assertTODO(expected, $result->getDkimAttributes());
        // self::assertTODO(expected, $result->getMailFromAttributes());
        // self::assertTODO(expected, $result->getPolicies());
        // self::assertTODO(expected, $result->getTags());
        self::assertSame('changeIt', $result->getConfigurationSetName());
        self::assertSame('changeIt', $result->getVerificationStatus());
        // self::assertTODO(expected, $result->getVerificationInfo());
    }
}
