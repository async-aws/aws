<?php

namespace AsyncAws\Ecr\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ecr\EcrClient;
use AsyncAws\Ecr\Input\GetAuthorizationTokenRequest;

class EcrClientTest extends TestCase
{
    public function testGetAuthorizationToken(): void
    {
        $client = $this->getClient();

        $input = new GetAuthorizationTokenRequest([
            'registryIds' => ['change me'],
        ]);
        $result = $client->GetAuthorizationToken($input);

        $result->resolve();

        //self::assertTODO(expected, $result->getAuthorizationData());
    }

    private function getClient(): EcrClient
    {
        self::markTestSkipped('There is no docker image available for Ecr.');

        return new EcrClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
