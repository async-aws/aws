<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Result\CreateVectorBucketOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateVectorBucketOutputTest extends TestCase
{
    public function testCreateVectorBucketOutput(): void
    {
        $response = new SimpleMockedResponse('{"vectorBucketArn":"arn:aws:s3vectors:us-east-1:123:bucket/my-bucket"}');

        $client = new MockHttpClient($response);
        $result = new CreateVectorBucketOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertInstanceOf(CreateVectorBucketOutput::class, $result);
    }
}
