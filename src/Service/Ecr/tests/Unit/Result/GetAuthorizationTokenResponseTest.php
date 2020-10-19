<?php

namespace AsyncAws\Ecr\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ecr\Result\GetAuthorizationTokenResponse;
use AsyncAws\Ecr\ValueObject\AuthorizationData;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetAuthorizationTokenResponseTest extends TestCase
{
    public function testGetAuthorizationTokenResponse(): void
    {
        $response = new SimpleMockedResponse('{
            "authorizationData": [
                {
                    "authorizationToken": "QVdTOkN...",
                    "expiresAt": "1602616356",
                    "proxyEndpoint": "https://012345678910.dkr.ecr.us-east-1.amazonaws.com"
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new GetAuthorizationTokenResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertEquals([AuthorizationData::create([
            'authorizationToken' => 'QVdTOkN...',
            'expiresAt' => \DateTimeImmutable::createFromFormat('U', '1602616356'),
            'proxyEndpoint' => 'https://012345678910.dkr.ecr.us-east-1.amazonaws.com',
        ])], $result->getAuthorizationData());
    }
}
