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
        // see https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_CreateEmailIdentity.html
        $response = new SimpleMockedResponse('{
            "IdentityType": "DOMAIN",
            "VerifiedForSendingStatus": false,
            "DkimAttributes": {
                "SigningEnabled": true,
                "Status": "PENDING",
                "Tokens": ["token1", "token2", "token3"],
                "SigningAttributesOrigin": "AWS_SES"
            }
        }');

        $client = new MockHttpClient($response);
        $result = new CreateEmailIdentityResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('DOMAIN', $result->getIdentityType());
        self::assertFalse($result->getVerifiedForSendingStatus());

        $dkimAttributes = $result->getDkimAttributes();
        self::assertTrue($dkimAttributes->getSigningEnabled());
        self::assertSame('PENDING', $dkimAttributes->getStatus());
        self::assertSame(['token1', 'token2', 'token3'], $dkimAttributes->getTokens());
        self::assertSame('AWS_SES', $dkimAttributes->getSigningAttributesOrigin());
    }
}
