<?php

namespace AsyncAws\CloudFront\Tests\Unit;

use AsyncAws\CloudFront\CloudFrontClient;
use AsyncAws\CloudFront\Input\CreateInvalidationRequest;
use AsyncAws\CloudFront\Result\CreateInvalidationResult;
use AsyncAws\CloudFront\ValueObject\InvalidationBatch;
use AsyncAws\CloudFront\ValueObject\Paths;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class CloudFrontClientTest extends TestCase
{
    public function testCreateInvalidation2019_03_26(): void
    {
        $client = new CloudFrontClient([], new NullProvider(), new MockHttpClient());

        $input = new CreateInvalidationRequest([
            'DistributionId' => 'change me',
            'InvalidationBatch' => new InvalidationBatch([
                'Paths' => new Paths([
                    'Quantity' => 1337,

                ]),
                'CallerReference' => 'change me',
            ]),
        ]);
        $result = $client->CreateInvalidation2019_03_26($input);

        self::assertInstanceOf(CreateInvalidationResult::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
