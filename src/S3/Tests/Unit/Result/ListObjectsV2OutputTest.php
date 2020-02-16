<?php

declare(strict_types=1);

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\S3\Result\ListObjectsV2Output;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class ListObjectsV2OutputTest extends TestCase
{
    public function testNoObjects()
    {
        $response = new SimpleMockedResponse(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
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
XML
);

        $result = new ListObjectsV2Output($response, new MockHttpClient());

        $content = $result->getContents(true);
        self::assertCount(0, $content);
    }
}
