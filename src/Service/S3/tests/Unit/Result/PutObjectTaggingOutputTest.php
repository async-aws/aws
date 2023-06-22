<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\PutObjectTaggingOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class PutObjectTaggingOutputTest extends TestCase
{
    public function testPutObjectTaggingOutput(): void
    {
        $response = new SimpleMockedResponse('', ['x-amz-version-id' => 'ydlaNkwWm0SfKJR.T1b1fIdPRbldTYRI']);

        $client = new MockHttpClient($response);
        $result = new PutObjectTaggingOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('ydlaNkwWm0SfKJR.T1b1fIdPRbldTYRI', $result->getVersionId());
    }

    public function testPutObjectTaggingOutputWithoutVersion(): void
    {
        $response = new SimpleMockedResponse('');

        $client = new MockHttpClient($response);
        $result = new PutObjectTaggingOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertNull($result->getVersionId());
    }
}
