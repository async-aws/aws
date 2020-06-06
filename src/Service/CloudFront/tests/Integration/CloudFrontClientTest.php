<?php

namespace AsyncAws\CloudFront\Tests\Integration;

use AsyncAws\CloudFront\CloudFrontClient;
use AsyncAws\CloudFront\Input\CreateInvalidationRequest;
use AsyncAws\CloudFront\ValueObject\InvalidationBatch;
use AsyncAws\CloudFront\ValueObject\Paths;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;

class CloudFrontClientTest extends TestCase
{
    public function testCreateInvalidation(): void
    {
        $client = $this->getClient();

        $input = new CreateInvalidationRequest([
            'DistributionId' => 'change me',
            'InvalidationBatch' => new InvalidationBatch([
                'Paths' => new Paths([
                    'Quantity' => 1337,
                    'Items' => ['change me'],
                ]),
                'CallerReference' => 'change me',
            ]),
        ]);
        $result = $client->createInvalidation($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getLocation());
        // self::assertTODO(expected, $result->getInvalidation());
    }

    private function getClient(): CloudFrontClient
    {
        self::markTestSkipped('Not implemented');

        return new CloudFrontClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
