<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\CreateBucketOutput;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateBucketOutputTest extends TestCase
{
    public function testCreateBucketOutput(): void
    {
        self::markTestIncomplete('Not implemented');

        // see example-1.json from SDK
        $response = new SimpleMockedResponse('<Location>/examplebucket</Location>');

        $result = new CreateBucketOutput($response, new MockHttpClient());

        self::assertSame('changeIt', $result->getLocation());
    }
}
