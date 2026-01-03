<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Result\CreateIndexOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateIndexOutputTest extends TestCase
{
    public function testCreateIndexOutput(): void
    {
        $response = new SimpleMockedResponse('{"indexArn":"arn:aws:s3vectors:us-east-1:123456789012:index/my-index"}');

        $client = new MockHttpClient($response);
        $result = new CreateIndexOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertInstanceOf(CreateIndexOutput::class, $result);
    }
}
