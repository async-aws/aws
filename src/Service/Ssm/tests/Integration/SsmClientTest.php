<?php

namespace AsyncAws\Ssm\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Input\PutParameterRequest;
use AsyncAws\Ssm\SsmClient;
use AsyncAws\Ssm\ValueObject\Tag;

class SsmClientTest extends TestCase
{
    public function testPutParameter(): void
    {
        $client = $this->getClient();

        $input = new PutParameterRequest([
            'Name' => 'change me',
            'Description' => 'change me',
            'Value' => 'change me',
            'Type' => 'change me',
            'KeyId' => 'change me',
            'Overwrite' => false,
            'AllowedPattern' => 'change me',
            'Tags' => [new Tag([
                'Key' => 'change me',
                'Value' => 'change me',
            ])],
            'Tier' => 'change me',
            'Policies' => 'change me',
        ]);
        $result = $client->PutParameter($input);

        $result->resolve();

        self::assertSame(1337, $result->getVersion());
        self::assertSame('changeIt', $result->getTier());
    }

    private function getClient(): SsmClient
    {
        self::fail('Not implemented');

        return new SsmClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
