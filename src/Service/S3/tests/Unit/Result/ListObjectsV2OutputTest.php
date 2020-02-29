<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\S3\Result\ListObjectsV2Output;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class ListObjectsV2OutputTest extends TestCase
{
    public function testListObjectsV2Output(): void
    {
        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
<ListBucketResult xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
    <Name>async-aws-test</Name>
    <Prefix>travis-ci/a7c9995c116874232f86/</Prefix>
    <KeyCount>1</KeyCount>
    <MaxKeys>1000</MaxKeys>
    <Delimiter>/</Delimiter>
    <IsTruncated>false</IsTruncated>
    <CommonPrefixes>
        <Prefix>travis-ci/a7c9995c116874232f86/some/</Prefix>
    </CommonPrefixes>
</ListBucketResult>');

        $client = new MockHttpClient($response);
        $result = new ListObjectsV2Output($client->request('POST', 'http://localhost'), $client);

        self::assertFalse($result->getIsTruncated());
        self::assertEquals('async-aws-test', $result->getName());
        self::assertEquals('travis-ci/a7c9995c116874232f86/', $result->getPrefix());
        self::assertEquals('/', $result->getDelimiter());
        self::assertEquals(1000, $result->getMaxKeys());

        self::assertEquals(1, $result->getKeyCount());
        self::assertNull($result->getContinuationToken());
        self::assertNull($result->getNextContinuationToken());
        self::assertNull($result->getStartAfter());
    }

    public function testNoObjects()
    {
        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
        <ListBucketResult xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
            <Name>flysystem-test-bucket</Name>
            <Prefix>travis-ci/102a8a14f41cc3cf7056/</Prefix>
            <Marker/>
            <MaxKeys>1000</MaxKeys>
            <IsTruncated>false</IsTruncated>
            <CommonPrefixes>
                <Prefix>travis-ci/102a8a14f41cc3cf7056/some/</Prefix>
            </CommonPrefixes>
        </ListBucketResult>
        ');

        $client = new MockHttpClient($response);
        $result = new ListObjectsV2Output($client->request('POST', 'http://localhost'), $client);

        $content = $result->getContents(true);
        self::assertCount(0, $content);
    }
}
