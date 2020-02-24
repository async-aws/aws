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

        // see https://docs.aws.amazon.com/SERVICE/latest/APIReference/API_METHOD.html
        $response = new SimpleMockedResponse('<change>it</change>');

        $result = new DeleteObjectOutput($response, new MockHttpClient());

        self::assertFalse($result->getDeleteMarker());
        self::assertSame('changeIt', $result->getVersionId());
        self::assertSame('changeIt', $result->getRequestCharged());
    }
}
