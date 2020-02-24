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

        $client = new MockHttpClient($response);
        $result = new CreateBucketOutput($client->request('POST', 'http://localhost'), $client);

        self::assertSame('changeIt', $result->getLocation());
    }
}
