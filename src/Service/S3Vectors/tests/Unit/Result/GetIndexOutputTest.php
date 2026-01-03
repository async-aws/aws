<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Result\GetIndexOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetIndexOutputTest extends TestCase
{
    public function testGetIndexOutput(): void
    {
        $response = new SimpleMockedResponse('{"index":{"vectorBucketName":"bucket1","indexName":"index1","indexArn":"arn:aws:s3vectors:us-east-1:123:index/index1","creationTime":1690000000.123456,"dataType":"float32","dimension":128,"distanceMetric":"cosine","metadataConfiguration":{"nonFilterableMetadataKeys":["k1"]}}}');

        $client = new MockHttpClient($response);
        $result = new GetIndexOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertInstanceOf(GetIndexOutput::class, $result);
    }
}
