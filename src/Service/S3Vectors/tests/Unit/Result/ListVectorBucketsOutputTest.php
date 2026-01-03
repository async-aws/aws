<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Input\ListVectorBucketsInput;
use AsyncAws\S3Vectors\Result\ListVectorBucketsOutput;
use AsyncAws\S3Vectors\S3VectorsClient;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListVectorBucketsOutputTest extends TestCase
{
    public function testListVectorBucketsOutput(): void
    {
        $response = new SimpleMockedResponse('{"nextToken":"tok-1","vectorBuckets":[{"vectorBucketName":"bucket-1","vectorBucketArn":"arn:aws:s3vectors:us-east-1:123:bucket/bucket-1","creationTime":1690000001.654321}]}');

        $client = new MockHttpClient($response);
        $result = new ListVectorBucketsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new S3VectorsClient(), new ListVectorBucketsInput([]));

        self::assertInstanceOf(ListVectorBucketsOutput::class, $result);
    }
}
