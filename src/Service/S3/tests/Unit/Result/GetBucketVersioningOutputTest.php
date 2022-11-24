<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\GetBucketVersioningOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetBucketVersioningOutputTest extends TestCase
{
    public function testGetBucketVersioningOutput(): void
    {
        self::fail('Not implemented');

        // see example-1.json from SDK
        $response = new SimpleMockedResponse('<MFADelete>Disabled</MFADelete>');

        $client = new MockHttpClient($response);
        $result = new GetBucketVersioningOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('changeIt', $result->getStatus());
        self::assertSame('changeIt', $result->getMFADelete());
    }
}
