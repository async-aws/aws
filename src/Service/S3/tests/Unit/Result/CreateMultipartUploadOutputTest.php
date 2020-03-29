<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\CreateMultipartUploadOutput;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateMultipartUploadOutputTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_CreateMultipartUpload.html#API_CreateMultipartUpload_Examples
     */
    public function testCreateMultipartUploadOutput(): void
    {
        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
<InitiateMultipartUploadResult xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
  <Bucket>example-bucket</Bucket>
  <Key>example-object</Key>
  <UploadId>VXBsb2FkIElEIGZvciA2aWWpbmcncyBteS1tb3ZpZS5tMnRzIHVwbG9hZA</UploadId>
</InitiateMultipartUploadResult>');

        $client = new MockHttpClient($response);
        $result = new CreateMultipartUploadOutput(new Response($client->request('POST', 'http://localhost'), $client));

        self::assertSame('example-bucket', $result->getBucket());
        self::assertSame('example-object', $result->getKey());
        self::assertSame('VXBsb2FkIElEIGZvciA2aWWpbmcncyBteS1tb3ZpZS5tMnRzIHVwbG9hZA', $result->getUploadId());
    }
}
