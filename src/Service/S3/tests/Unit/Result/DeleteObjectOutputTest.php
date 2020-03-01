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
        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteObject.html
        $response = new SimpleMockedResponse('', [
            'x-amz-request-charged' => 'requester',
            'x-amz-version-id' => '3/L4kqtJlcpXroDTDmJ+rmSpXd3dIbrHY+MTRCxf3vjVBH40Nr8X8gdRQBpUMLUo',
            'x-amz-delete-marker' => 'true',
        ]);

        $client = new MockHttpClient($response);
        $result = new DeleteObjectOutput($client->request('POST', 'http://localhost'), $client);

        self::assertTrue($result->getDeleteMarker());
        self::assertSame('3/L4kqtJlcpXroDTDmJ+rmSpXd3dIbrHY+MTRCxf3vjVBH40Nr8X8gdRQBpUMLUo', $result->getVersionId());
        self::assertSame('requester', $result->getRequestCharged());
    }
}
