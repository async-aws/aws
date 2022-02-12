<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\ListBucketsRequest;
use AsyncAws\S3\Result\ListBucketsOutput;
use AsyncAws\S3\S3Client;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListBucketsOutputTest extends TestCase
{
    public function testListBucketsOutput(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse(
            '<?xml version="1.0" encoding="UTF-8"?>

            <ListAllMyBucketsResult xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
                <Owner>
                    <ID>123456789</ID>
                    <DisplayName>async-aws</DisplayName>
                </Owner>
                <Buckets>
                    <Bucket>
                        <CreationDate>2021-12-07T14:36:01.000Z</CreationDate>
                        <Name>examplebucke</Name>
                    </Bucket>
                    <Bucket>
                        <CreationDate>2021-12-07T14:36:01.000Z</CreationDate>
                        <Name>examplebucket2</Name>
                    </Bucket>
                    <Bucket>
                        <CreationDate>2021-12-07T14:36:01.000Z</CreationDate>
                        <Name>examplebucket3</Name>
                    </Bucket>
                </Buckets>
            </ListAllMyBucketsResult>
        ');

        $client = new MockHttpClient($response);
        $result = new ListBucketsOutput(
            new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()),
            new S3Client(),
            new ListBucketsRequest([])
        );

        self::assertSame('async-aws', $result->getOwner()->getDisplayName());
        $buckets = $result->getBuckets();
        self::assertCount(3, $buckets);
        self::assertSame('examplebucket2', $buckets[1]->getName());
    }
}
