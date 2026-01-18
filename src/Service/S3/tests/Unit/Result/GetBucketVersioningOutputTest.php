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
        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
            <VersioningConfiguration xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
                <Status>Enabled</Status>
             </VersioningConfiguration>
        ');

        $client = new MockHttpClient($response);
        $result = new GetBucketVersioningOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('Enabled', $result->getStatus());
    }
}
