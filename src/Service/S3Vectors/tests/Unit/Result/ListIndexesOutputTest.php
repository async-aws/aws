<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Input\ListIndexesInput;
use AsyncAws\S3Vectors\Result\ListIndexesOutput;
use AsyncAws\S3Vectors\S3VectorsClient;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListIndexesOutputTest extends TestCase
{
    public function testListIndexesOutput(): void
    {
        $response = new SimpleMockedResponse('{"nextToken":"token123","indexes":[{"vectorBucketName":"bucket1","indexName":"index1","indexArn":"arn:aws:s3vectors:us-east-1:123:index/index1","creationTime":1690000000.123456}]}');

        $client = new MockHttpClient($response);
        $result = new ListIndexesOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new S3VectorsClient(), new ListIndexesInput([]));

        self::assertInstanceOf(ListIndexesOutput::class, $result);
    }
}
