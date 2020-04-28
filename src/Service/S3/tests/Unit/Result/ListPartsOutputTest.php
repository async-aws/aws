<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\ListPartsOutput;
use AsyncAws\S3\ValueObject\Part;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListPartsOutputTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListParts.html#API_ListParts_Examples
     */
    public function testListPartsOutput(): void
    {
        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
<ListPartsResult xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
  <Bucket>example-bucket</Bucket>
  <Key>example-object</Key>
  <UploadId>XXBsb2FkIElEIGZvciBlbHZpbmcncyVcdS1tb3ZpZS5tMnRzEEEwbG9hZA</UploadId>
  <Initiator>
      <ID>arn:aws:iam::111122223333:user/some-user-11116a31-17b5-4fb7-9df5-b288870f11xx</ID>
      <DisplayName>umat-user-11116a31-17b5-4fb7-9df5-b288870f11xx</DisplayName>
  </Initiator>
  <Owner>
    <ID>75aa57f09aa0c8caeab4f8c24e99d10f8e7faeebf76c078efc7c6caea54ba06a</ID>
    <DisplayName>someName</DisplayName>
  </Owner>
  <StorageClass>STANDARD</StorageClass>
  <PartNumberMarker>1</PartNumberMarker>
  <NextPartNumberMarker>3</NextPartNumberMarker>
  <MaxParts>2</MaxParts>
  <IsTruncated>true</IsTruncated>
  <Part>
    <PartNumber>2</PartNumber>
    <LastModified>2010-11-10T20:48:34.000Z</LastModified>
    <ETag>"7778aef83f66abc1fa1e8477f296d394"</ETag>
    <Size>10485760</Size>
  </Part>
  <Part>
    <PartNumber>3</PartNumber>
    <LastModified>2010-11-10T20:48:33.000Z</LastModified>
    <ETag>"aaaa18db4cc2f85cedef654fccc4a4x8"</ETag>
    <Size>10485760</Size>
  </Part>
</ListPartsResult>');

        $client = new MockHttpClient($response);
        $result = new ListPartsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('example-bucket', $result->getBucket());
        self::assertSame('example-object', $result->getKey());
        self::assertSame('XXBsb2FkIElEIGZvciBlbHZpbmcncyVcdS1tb3ZpZS5tMnRzEEEwbG9hZA', $result->getUploadId());
        self::assertSame(1, $result->getPartNumberMarker());
        self::assertSame(3, $result->getNextPartNumberMarker());
        self::assertSame(2, $result->getMaxParts());
        self::assertTrue($result->getIsTruncated());
        self::assertSame('STANDARD', $result->getStorageClass());

        self::assertEquals('75aa57f09aa0c8caeab4f8c24e99d10f8e7faeebf76c078efc7c6caea54ba06a', $result->getOwner()->getID());
        self::assertEquals('someName', $result->getOwner()->getDisplayName());
        self::assertEquals('arn:aws:iam::111122223333:user/some-user-11116a31-17b5-4fb7-9df5-b288870f11xx', $result->getInitiator()->getID());
        self::assertEquals('umat-user-11116a31-17b5-4fb7-9df5-b288870f11xx', $result->getInitiator()->getDisplayName());

        $part = iterator_to_array($result->getParts(true));
        /** @var Part $part */
        $part = $part[1];
        self::assertEquals('3', $part->getPartNumber());
        self::assertEquals('10485760', $part->getSize());
        self::assertEquals('"aaaa18db4cc2f85cedef654fccc4a4x8"', $part->getETag());
        self::assertEquals(new \DateTimeImmutable('2010-11-10T20:48:33.000Z'), $part->getLastModified());
    }
}
