<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\ListBucketsRequest;
use AsyncAws\S3\Result\ListBucketsOutput;
use AsyncAws\S3\S3Client;
use AsyncAws\S3\ValueObject\Bucket;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListBucketsOutputTest extends TestCase
{
    public function testListBucketsOutput(): void
    {
        $response = new SimpleMockedResponse('<ListAllMyBucketsResult>
    <Buckets>
        <Bucket>
            <CreationDate>2012-02-15T21:03:02.000Z</CreationDate>
            <Name>examplebucket</Name>
        </Bucket>
    </Buckets>
</ListAllMyBucketsResult>');

        $client = new MockHttpClient($response);
        $result = new ListBucketsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new S3Client(), new ListBucketsRequest([]));

        $buckets = iterator_to_array($result->getBuckets(true));
        self::assertCount(1, $buckets);

        $firstBucket = $buckets[0];

        self::assertInstanceOf(Bucket::class, $firstBucket);
        self::assertSame('examplebucket', $firstBucket->getName());
        self::assertSame(1329339782, $firstBucket->getCreationDate()->getTimestamp());

        self::assertNull($result->getOwner());
    }
}
