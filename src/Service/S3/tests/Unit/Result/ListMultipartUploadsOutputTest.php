<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\ListMultipartUploadsOutput;
use AsyncAws\S3\ValueObject\MultipartUpload;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListMultipartUploadsOutputTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_ListMultipartUploads.html#API_ListMultipartUploads_Examples
     */
    public function testListMultipartUploadsOutput(): void
    {
        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
<ListMultipartUploadsResult xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
  <Bucket>bucket</Bucket>
  <KeyMarker></KeyMarker>
  <UploadIdMarker></UploadIdMarker>
  <NextKeyMarker>my-movie.m2ts</NextKeyMarker>
  <NextUploadIdMarker>YW55IGlkZWEgd2h5IGVsdmluZydzIHVwbG9hZCBmYWlsZWQ</NextUploadIdMarker>
  <MaxUploads>3</MaxUploads>
  <IsTruncated>true</IsTruncated>
  <Upload>
    <Key>my-divisor</Key>
    <UploadId>XMgbGlrZSBlbHZpbmcncyBub3QgaGF2aW5nIG11Y2ggbHVjaw</UploadId>
    <Initiator>
      <ID>arn:aws:iam::111122223333:user/user1-11111a31-17b5-4fb7-9df5-b111111f13de</ID>
      <DisplayName>user1-11111a31-17b5-4fb7-9df5-b111111f13de</DisplayName>
    </Initiator>
    <Owner>
      <ID>75aa57f09aa0c8caeab4f8c24e99d10f8e7faeebf76c078efc7c6caea54ba06a</ID>
      <DisplayName>OwnerDisplayName</DisplayName>
    </Owner>
    <StorageClass>STANDARD</StorageClass>
    <Initiated>2010-11-10T20:48:33.000Z</Initiated>
  </Upload>
  <Upload>
    <Key>my-movie.m2ts</Key>
    <UploadId>VXBsb2FkIElEIGZvciBlbHZpbmcncyBteS1tb3ZpZS5tMnRzIHVwbG9hZA</UploadId>
    <Initiator>
      <ID>b1d16700c70b0b05597d7acd6a3f92be</ID>
      <DisplayName>InitiatorDisplayName</DisplayName>
    </Initiator>
    <Owner>
      <ID>b1d16700c70b0b05597d7acd6a3f92be</ID>
      <DisplayName>OwnerDisplayName</DisplayName>
    </Owner>
    <StorageClass>STANDARD</StorageClass>
    <Initiated>2010-11-10T20:48:33.000Z</Initiated>
  </Upload>
</ListMultipartUploadsResult>');

        $client = new MockHttpClient($response);
        $result = new ListMultipartUploadsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('bucket', $result->getBucket());
        self::assertSame('', $result->getKeyMarker());
        self::assertSame('', $result->getUploadIdMarker());
        self::assertSame('my-movie.m2ts', $result->getNextKeyMarker());
        self::assertSame('YW55IGlkZWEgd2h5IGVsdmluZydzIHVwbG9hZCBmYWlsZWQ', $result->getNextUploadIdMarker());
        self::assertSame(3, $result->getMaxUploads());
        self::assertTrue($result->getIsTruncated());
        $uploads = iterator_to_array($result->getUploads(true));
        self::assertCount(2, $uploads);

        /** @var MultipartUpload $upload */
        $upload = $uploads[1];
        self::assertEquals('my-movie.m2ts', $upload->getKey());
        self::assertEquals('VXBsb2FkIElEIGZvciBlbHZpbmcncyBteS1tb3ZpZS5tMnRzIHVwbG9hZA', $upload->getUploadId());
        self::assertEquals('STANDARD', $upload->getStorageClass());
        self::assertEquals(new \DateTimeImmutable('2010-11-10T20:48:33.000Z'), $upload->getInitiated());
        self::assertEquals('b1d16700c70b0b05597d7acd6a3f92be', $upload->getOwner()->getID());
        self::assertEquals('OwnerDisplayName', $upload->getOwner()->getDisplayName());
        self::assertEquals('b1d16700c70b0b05597d7acd6a3f92be', $upload->getInitiator()->getID());
        self::assertEquals('InitiatorDisplayName', $upload->getInitiator()->getDisplayName());
    }
}
