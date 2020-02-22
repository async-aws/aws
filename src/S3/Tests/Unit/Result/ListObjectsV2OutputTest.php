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
        self::markTestIncomplete('Not implemented');

        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
            <ChangeIt/>
        ');

        $result = new ListObjectsV2Output($response, new MockHttpClient());

        self::assertFalse($result->getIsTruncated());
        // self::assertTODO(expected, $result->getContents());
        self::assertStringContainsString('change it', $result->getName());
        self::assertStringContainsString('change it', $result->getPrefix());
        self::assertStringContainsString('change it', $result->getDelimiter());
        self::assertSame(1337, $result->getMaxKeys());
        // self::assertTODO(expected, $result->getCommonPrefixes());
        self::assertStringContainsString('change it', $result->getEncodingType());
        self::assertSame(1337, $result->getKeyCount());
        self::assertStringContainsString('change it', $result->getContinuationToken());
        self::assertStringContainsString('change it', $result->getNextContinuationToken());
        self::assertStringContainsString('change it', $result->getStartAfter());
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

        $result = new ListObjectsV2Output($response, new MockHttpClient());

        $content = $result->getContents(true);
        self::assertCount(0, $content);
    }
}
