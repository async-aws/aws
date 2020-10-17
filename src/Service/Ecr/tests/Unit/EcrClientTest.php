<?php

namespace AsyncAws\Ecr\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ecr\EcrClient;
use AsyncAws\Ecr\Input\GetAuthorizationTokenRequest;
use AsyncAws\Ecr\Result\GetAuthorizationTokenResponse;
use Symfony\Component\HttpClient\MockHttpClient;

class EcrClientTest extends TestCase
{
    public function testGetAuthorizationToken(): void
    {
        $client = new EcrClient([], new NullProvider(), new MockHttpClient());

        $input = new GetAuthorizationTokenRequest([

        ]);
        $result = $client->GetAuthorizationToken($input);

        self::assertInstanceOf(GetAuthorizationTokenResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
