<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Result\GetVectorBucketOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetVectorBucketOutputTest extends TestCase
{
    public function testGetVectorBucketOutput(): void
    {
        $response = new SimpleMockedResponse('{"vectorBucket":{"vectorBucketName":"bucket1","vectorBucketArn":"arn:aws:s3vectors:us-east-1:123:bucket/bucket1","creationTime":1690000002.000000,"encryptionConfiguration":{"sseType":"AES256"}}}');

        $client = new MockHttpClient($response);
        $result = new GetVectorBucketOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertInstanceOf(GetVectorBucketOutput::class, $result);
    }
}
