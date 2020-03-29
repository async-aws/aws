<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\AbortMultipartUploadOutput;
use Symfony\Component\HttpClient\MockHttpClient;

class AbortMultipartUploadOutputTest extends TestCase
{
    public function testAbortMultipartUploadOutput(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/s3/latest/APIReference/API_AbortMultipartUpload.html
        $response = new SimpleMockedResponse('<?xml version="1.0"?>
        <root/>
        ');

        $client = new MockHttpClient($response);
        $result = new AbortMultipartUploadOutput(new Response($client->request('POST', 'http://localhost'), $client));

        self::assertSame('changeIt', $result->getRequestCharged());
    }
}
