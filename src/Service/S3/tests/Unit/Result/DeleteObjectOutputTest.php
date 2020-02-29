<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\DeleteObjectOutput;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteObjectOutputTest extends TestCase
{
    public function testDeleteObjectOutput(): void
    {
        self::markTestIncomplete('Not implemented');

        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteObjects.html
        $response = new SimpleMockedResponse('<change>it</change>');

        $client = new MockHttpClient($response);
        $result = new DeleteObjectOutput($client->request('POST', 'http://localhost'), $client);

        self::assertFalse($result->getDeleteMarker());
        self::assertSame('changeIt', $result->getVersionId());
        self::assertSame('changeIt', $result->getRequestCharged());
    }
}
