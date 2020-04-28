<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\CompleteMultipartUploadOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CompleteMultipartUploadOutputTest extends TestCase
{
    public function testCompleteMultipartUploadOutput(): void
    {
        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_CompleteMultipartUpload.html#API_CompleteMultipartUpload_Examples
        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
<CompleteMultipartUploadResult xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
 <Location>http://Example-Bucket.s3.eu-central-1.amazonaws.com/Example-Object</Location>
 <Bucket>Example-Bucket</Bucket>
 <Key>Example-Object</Key>
 <ETag>"3858f62230ac3c915f300c664312c11f-9"</ETag>
</CompleteMultipartUploadResult>');

        $client = new MockHttpClient($response);
        $result = new CompleteMultipartUploadOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('http://Example-Bucket.s3.eu-central-1.amazonaws.com/Example-Object', $result->getLocation());
        self::assertSame('Example-Bucket', $result->getBucket());
        self::assertSame('Example-Object', $result->getKey());
        self::assertSame('"3858f62230ac3c915f300c664312c11f-9"', $result->getETag());
    }
}
