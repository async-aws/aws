<?php

namespace AsyncAws\S3\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\S3\Input\AccessControlPolicy;
use AsyncAws\S3\Input\CopyObjectRequest;
use AsyncAws\S3\Input\CreateBucketConfiguration;
use AsyncAws\S3\Input\CreateBucketRequest;
use AsyncAws\S3\Input\DeleteObjectRequest;
use AsyncAws\S3\Input\GetObjectAclRequest;
use AsyncAws\S3\Input\GetObjectRequest;
use AsyncAws\S3\Input\Grant;
use AsyncAws\S3\Input\Grantee;
use AsyncAws\S3\Input\HeadObjectRequest;
use AsyncAws\S3\Input\ListObjectsV2Request;
use AsyncAws\S3\Input\Owner;
use AsyncAws\S3\Input\PutObjectAclRequest;
use AsyncAws\S3\Input\PutObjectRequest;
use AsyncAws\S3\Result\AwsObject;
use AsyncAws\S3\Result\CommonPrefix;
use AsyncAws\S3\Result\PutObjectOutput;
use AsyncAws\S3\S3Client;
use PHPUnit\Framework\TestCase;

class S3ClientTest extends TestCase
{
    public function testBasicUploadDownload()
    {
        $s3 = $this->getClient();
        $input = new PutObjectRequest();
        $fileBody = 'foobar';
        $input->setBucket('foo')
            ->setKey('bar')
            ->setBody($fileBody);
        $result = $s3->putObject($input);

        $result->resolve();
        $info = $result->info();
        self::assertEquals(200, $info['status']);

        // Test get object
        $input = new GetObjectRequest();
        $input->setBucket('foo')
            ->setKey('bar');
        $result = $s3->getObject($input);
        $body = $result->getBody()->getContentAsString();

        self::assertEquals($fileBody, $body);

        // Test Delete object
        $result = $s3->deleteObject(['Bucket' => 'foo', 'Key' => 'bar']);
        $result->resolve();
        $info = $result->info();
        self::assertEquals(204, $info['status']);
    }

    public function testCopyObject(): void
    {
        self::markTestIncomplete('Not implemented');

        $client = $this->getClient();

        $input = new CopyObjectRequest([
            'ACL' => 'change me',
            'Bucket' => 'change me',
            'CacheControl' => 'change me',
            'ContentDisposition' => 'change me',
            'ContentEncoding' => 'change me',
            'ContentLanguage' => 'change me',
            'ContentType' => 'change me',
            'CopySource' => 'change me',
            'CopySourceIfMatch' => 'change me',
            'CopySourceIfModifiedSince' => new \DateTimeImmutable(),
            'CopySourceIfNoneMatch' => 'change me',
            'CopySourceIfUnmodifiedSince' => new \DateTimeImmutable(),
            'Expires' => new \DateTimeImmutable(),
            'GrantFullControl' => 'change me',
            'GrantRead' => 'change me',
            'GrantReadACP' => 'change me',
            'GrantWriteACP' => 'change me',
            'Key' => 'change me',
            'Metadata' => ['change me' => 'change me'],
            'MetadataDirective' => 'change me',
            'TaggingDirective' => 'change me',
            'ServerSideEncryption' => 'change me',
            'StorageClass' => 'change me',
            'WebsiteRedirectLocation' => 'change me',
            'SSECustomerAlgorithm' => 'change me',
            'SSECustomerKey' => 'change me',
            'SSECustomerKeyMD5' => 'change me',
            'SSEKMSKeyId' => 'change me',
            'SSEKMSEncryptionContext' => 'change me',
            'CopySourceSSECustomerAlgorithm' => 'change me',
            'CopySourceSSECustomerKey' => 'change me',
            'CopySourceSSECustomerKeyMD5' => 'change me',
            'RequestPayer' => 'change me',
            'Tagging' => 'change me',
            'ObjectLockMode' => 'change me',
            'ObjectLockRetainUntilDate' => new \DateTimeImmutable(),
            'ObjectLockLegalHoldStatus' => 'change me',
        ]);
        $result = $client->CopyObject($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getCopyObjectResult());
        self::assertStringContainsString('change it', $result->getExpiration());
        self::assertStringContainsString('change it', $result->getCopySourceVersionId());
        self::assertStringContainsString('change it', $result->getVersionId());
        self::assertStringContainsString('change it', $result->getServerSideEncryption());
        self::assertStringContainsString('change it', $result->getSSECustomerAlgorithm());
        self::assertStringContainsString('change it', $result->getSSECustomerKeyMD5());
        self::assertStringContainsString('change it', $result->getSSEKMSKeyId());
        self::assertStringContainsString('change it', $result->getSSEKMSEncryptionContext());
        self::assertStringContainsString('change it', $result->getRequestCharged());
    }

    public function testCreateBucket(): void
    {
        self::markTestIncomplete('Not implemented');

        $client = $this->getClient();

        $input = new CreateBucketRequest([
            'ACL' => 'change me',
            'Bucket' => 'change me',
            'CreateBucketConfiguration' => new CreateBucketConfiguration([
                'LocationConstraint' => 'change me',
            ]),
            'GrantFullControl' => 'change me',
            'GrantRead' => 'change me',
            'GrantReadACP' => 'change me',
            'GrantWrite' => 'change me',
            'GrantWriteACP' => 'change me',
            'ObjectLockEnabledForBucket' => false,
        ]);
        $result = $client->CreateBucket($input);

        $result->resolve();

        self::assertStringContainsString('change it', $result->getLocation());
    }

    public function testCreateDirectory()
    {
        $s3 = $this->getClient();

        $result = $s3->putObject([
            'Bucket' => 'foo',
            'Key' => 'bar/',
        ]);

        $result->resolve();
        $info = $result->info();
        self::assertEquals(200, $info['status']);
    }

    public function testDeleteObject(): void
    {
        self::markTestIncomplete('Not implemented');

        $client = $this->getClient();

        $input = new DeleteObjectRequest([
            'Bucket' => 'change me',
            'Key' => 'change me',
            'MFA' => 'change me',
            'VersionId' => 'change me',
            'RequestPayer' => 'change me',
            'BypassGovernanceRetention' => false,
        ]);
        $result = $client->DeleteObject($input);

        $result->resolve();

        self::assertFalse($result->getDeleteMarker());
        self::assertStringContainsString('change it', $result->getVersionId());
        self::assertStringContainsString('change it', $result->getRequestCharged());
    }

    public function testDeleteObjects()
    {
        self::markTestSkipped('The S3 Docker image does not implement DeleteObjects. https://github.com/jubos/fake-s3/issues/97');

        $s3 = $this->getClient();
        $bucket = 'foo';

        $result = $s3->deleteObjects([
            'Bucket' => $bucket,
            'Delete' => ['Objects' => [['Key' => 'foo/bar.txt'], ['Key' => 'foo/bix/xx.txt']]],
        ]);

        $result->resolve();
        $info = $result->info();
        self::assertEquals(204, $info['status']);
    }

    public function testGetFileNotExist()
    {
        $s3 = $this->getClient();

        // Test get object
        $input = new GetObjectRequest();
        $input->setBucket('foo')
            ->setKey('no_file');
        $result = $s3->getObject($input);
        $this->expectException(ClientException::class);
        $result->getBody();
    }

    public function testGetObject(): void
    {
        self::markTestIncomplete('Not implemented');

        $client = $this->getClient();

        $input = new GetObjectRequest([
            'Bucket' => 'change me',
            'IfMatch' => 'change me',
            'IfModifiedSince' => new \DateTimeImmutable(),
            'IfNoneMatch' => 'change me',
            'IfUnmodifiedSince' => new \DateTimeImmutable(),
            'Key' => 'change me',
            'Range' => 'change me',
            'ResponseCacheControl' => 'change me',
            'ResponseContentDisposition' => 'change me',
            'ResponseContentEncoding' => 'change me',
            'ResponseContentLanguage' => 'change me',
            'ResponseContentType' => 'change me',
            'ResponseExpires' => new \DateTimeImmutable(),
            'VersionId' => 'change me',
            'SSECustomerAlgorithm' => 'change me',
            'SSECustomerKey' => 'change me',
            'SSECustomerKeyMD5' => 'change me',
            'RequestPayer' => 'change me',
            'PartNumber' => 1337,
        ]);
        $result = $client->GetObject($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getBody());
        self::assertFalse($result->getDeleteMarker());
        self::assertStringContainsString('change it', $result->getAcceptRanges());
        self::assertStringContainsString('change it', $result->getExpiration());
        self::assertStringContainsString('change it', $result->getRestore());
        // self::assertTODO(expected, $result->getLastModified());
        self::assertSame(1337, $result->getContentLength());
        self::assertStringContainsString('change it', $result->getETag());
        self::assertSame(1337, $result->getMissingMeta());
        self::assertStringContainsString('change it', $result->getVersionId());
        self::assertStringContainsString('change it', $result->getCacheControl());
        self::assertStringContainsString('change it', $result->getContentDisposition());
        self::assertStringContainsString('change it', $result->getContentEncoding());
        self::assertStringContainsString('change it', $result->getContentLanguage());
        self::assertStringContainsString('change it', $result->getContentRange());
        self::assertStringContainsString('change it', $result->getContentType());
        // self::assertTODO(expected, $result->getExpires());
        self::assertStringContainsString('change it', $result->getWebsiteRedirectLocation());
        self::assertStringContainsString('change it', $result->getServerSideEncryption());
        // self::assertTODO(expected, $result->getMetadata());
        self::assertStringContainsString('change it', $result->getSSECustomerAlgorithm());
        self::assertStringContainsString('change it', $result->getSSECustomerKeyMD5());
        self::assertStringContainsString('change it', $result->getSSEKMSKeyId());
        self::assertStringContainsString('change it', $result->getStorageClass());
        self::assertStringContainsString('change it', $result->getRequestCharged());
        self::assertStringContainsString('change it', $result->getReplicationStatus());
        self::assertSame(1337, $result->getPartsCount());
        self::assertSame(1337, $result->getTagCount());
        self::assertStringContainsString('change it', $result->getObjectLockMode());
        // self::assertTODO(expected, $result->getObjectLockRetainUntilDate());
        self::assertStringContainsString('change it', $result->getObjectLockLegalHoldStatus());
    }

    public function testGetObjectAcl(): void
    {
        self::markTestIncomplete('Not implemented');

        $client = $this->getClient();

        $input = new GetObjectAclRequest([
            'Bucket' => 'change me',
            'Key' => 'change me',
            'VersionId' => 'change me',
            'RequestPayer' => 'change me',
        ]);
        $result = $client->GetObjectAcl($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getOwner());
        // self::assertTODO(expected, $result->getGrants());
        self::assertStringContainsString('change it', $result->getRequestCharged());
    }

    public function testHeadObject(): void
    {
        self::markTestIncomplete('Not implemented');

        $client = $this->getClient();

        $input = new HeadObjectRequest([
            'Bucket' => 'change me',
            'IfMatch' => 'change me',
            'IfModifiedSince' => new \DateTimeImmutable(),
            'IfNoneMatch' => 'change me',
            'IfUnmodifiedSince' => new \DateTimeImmutable(),
            'Key' => 'change me',
            'Range' => 'change me',
            'VersionId' => 'change me',
            'SSECustomerAlgorithm' => 'change me',
            'SSECustomerKey' => 'change me',
            'SSECustomerKeyMD5' => 'change me',
            'RequestPayer' => 'change me',
            'PartNumber' => 1337,
        ]);
        $result = $client->HeadObject($input);

        $result->resolve();

        self::assertFalse($result->getDeleteMarker());
        self::assertStringContainsString('change it', $result->getAcceptRanges());
        self::assertStringContainsString('change it', $result->getExpiration());
        self::assertStringContainsString('change it', $result->getRestore());
        // self::assertTODO(expected, $result->getLastModified());
        self::assertSame(1337, $result->getContentLength());
        self::assertStringContainsString('change it', $result->getETag());
        self::assertSame(1337, $result->getMissingMeta());
        self::assertStringContainsString('change it', $result->getVersionId());
        self::assertStringContainsString('change it', $result->getCacheControl());
        self::assertStringContainsString('change it', $result->getContentDisposition());
        self::assertStringContainsString('change it', $result->getContentEncoding());
        self::assertStringContainsString('change it', $result->getContentLanguage());
        self::assertStringContainsString('change it', $result->getContentType());
        // self::assertTODO(expected, $result->getExpires());
        self::assertStringContainsString('change it', $result->getWebsiteRedirectLocation());
        self::assertStringContainsString('change it', $result->getServerSideEncryption());
        // self::assertTODO(expected, $result->getMetadata());
        self::assertStringContainsString('change it', $result->getSSECustomerAlgorithm());
        self::assertStringContainsString('change it', $result->getSSECustomerKeyMD5());
        self::assertStringContainsString('change it', $result->getSSEKMSKeyId());
        self::assertStringContainsString('change it', $result->getStorageClass());
        self::assertStringContainsString('change it', $result->getRequestCharged());
        self::assertStringContainsString('change it', $result->getReplicationStatus());
        self::assertSame(1337, $result->getPartsCount());
        self::assertStringContainsString('change it', $result->getObjectLockMode());
        // self::assertTODO(expected, $result->getObjectLockRetainUntilDate());
        self::assertStringContainsString('change it', $result->getObjectLockLegalHoldStatus());
    }

    public function testListObjectsV2()
    {
        $s3 = $this->getClient();

        $requests = [];
        for ($i = 0; $i < 5; ++$i) {
            $requests[] = $s3->putObject(['Bucket' => 'foo', 'Key' => 'list/content-' . $i, 'Body' => 'test']);
            $requests[] = $s3->putObject(['Bucket' => 'foo', 'Key' => 'list/prefix-' . $i . '/file']);
        }
        \array_walk($requests, function (PutObjectOutput $response) {
            $response->resolve();
        });

        self::markTestIncomplete('The S3 image does not implement Pagination. https://github.com/jubos/fake-s3/issues/223');

        $input = (new ListObjectsV2Request())
            ->setBucket('foo')
            ->setPrefix('list/')
            //->setMaxKeys(2) // pagination is not implemented
            ->setDelimiter('/')
        ;

        $result = $s3->listObjectsV2($input);

        self::assertCount(10, $result);
        self::assertCount(5, $result->getCommonPrefixes());
        self::assertCount(5, $result->getContents());

        $files = \array_map(function (AwsObject $content) {
            return $content->getKey();
        }, \iterator_to_array($result->getContents()));
        $prefixes = \array_map(function (CommonPrefix $prefix) {
            return $prefix->getPrefix();
        }, \iterator_to_array($result->getCommonPrefixes()));

        self::assertContains('list/prefix-1/', $prefixes);
        self::assertContains('list/content-2', $files);
    }

    public function testPutObject(): void
    {
        self::markTestIncomplete('Not implemented');

        $client = $this->getClient();

        $input = new PutObjectRequest([
            'ACL' => 'change me',
            'Body' => 'change me',
            'Bucket' => 'change me',
            'CacheControl' => 'change me',
            'ContentDisposition' => 'change me',
            'ContentEncoding' => 'change me',
            'ContentLanguage' => 'change me',
            'ContentLength' => 1337,
            'ContentMD5' => 'change me',
            'ContentType' => 'change me',
            'Expires' => new \DateTimeImmutable(),
            'GrantFullControl' => 'change me',
            'GrantRead' => 'change me',
            'GrantReadACP' => 'change me',
            'GrantWriteACP' => 'change me',
            'Key' => 'change me',
            'Metadata' => ['change me' => 'change me'],
            'ServerSideEncryption' => 'change me',
            'StorageClass' => 'change me',
            'WebsiteRedirectLocation' => 'change me',
            'SSECustomerAlgorithm' => 'change me',
            'SSECustomerKey' => 'change me',
            'SSECustomerKeyMD5' => 'change me',
            'SSEKMSKeyId' => 'change me',
            'SSEKMSEncryptionContext' => 'change me',
            'RequestPayer' => 'change me',
            'Tagging' => 'change me',
            'ObjectLockMode' => 'change me',
            'ObjectLockRetainUntilDate' => new \DateTimeImmutable(),
            'ObjectLockLegalHoldStatus' => 'change me',
        ]);
        $result = $client->PutObject($input);

        $result->resolve();

        self::assertStringContainsString('change it', $result->getExpiration());
        self::assertStringContainsString('change it', $result->getETag());
        self::assertStringContainsString('change it', $result->getServerSideEncryption());
        self::assertStringContainsString('change it', $result->getVersionId());
        self::assertStringContainsString('change it', $result->getSSECustomerAlgorithm());
        self::assertStringContainsString('change it', $result->getSSECustomerKeyMD5());
        self::assertStringContainsString('change it', $result->getSSEKMSKeyId());
        self::assertStringContainsString('change it', $result->getSSEKMSEncryptionContext());
        self::assertStringContainsString('change it', $result->getRequestCharged());
    }

    public function testPutObjectAcl(): void
    {
        self::markTestIncomplete('Not implemented');

        $client = $this->getClient();

        $input = new PutObjectAclRequest([
            'ACL' => 'change me',
            'AccessControlPolicy' => new AccessControlPolicy([
                'Grants' => [new Grant([
                    'Grantee' => new Grantee([
                        'DisplayName' => 'change me',
                        'EmailAddress' => 'change me',
                        'ID' => 'change me',
                        'Type' => 'change me',
                        'URI' => 'change me',
                    ]),
                    'Permission' => 'change me',
                ])],
                'Owner' => new Owner([
                    'DisplayName' => 'change me',
                    'ID' => 'change me',
                ]),
            ]),
            'Bucket' => 'change me',
            'ContentMD5' => 'change me',
            'GrantFullControl' => 'change me',
            'GrantRead' => 'change me',
            'GrantReadACP' => 'change me',
            'GrantWrite' => 'change me',
            'GrantWriteACP' => 'change me',
            'Key' => 'change me',
            'RequestPayer' => 'change me',
            'VersionId' => 'change me',
        ]);
        $result = $client->PutObjectAcl($input);

        $result->resolve();

        self::assertStringContainsString('change it', $result->getRequestCharged());
    }

    public function testUploadFromClosure()
    {
        $parts = ['some ', 'content'];
        $content = implode('', $parts);
        $index = 0;
        $closure = \Closure::fromCallable(function () use ($parts, &$index) {
            return $parts[$index++] ?? '';
        });

        $s3 = $this->getClient();
        $input = new PutObjectRequest();
        $input->setBucket('foo')
            ->setKey('bar')
            ->setBody($closure);
        $result = $s3->putObject($input);

        $result->resolve();
        $info = $result->info();
        self::assertEquals(200, $info['status']);

        // Test get object
        $result = $s3->getObject(['Bucket' => 'foo', 'Key' => 'bar']);
        $body = $result->getBody()->getContentAsString();

        self::assertEquals($content, $body);
    }

    public function testUploadFromResource()
    {
        $resource = \fopen('php://temp', 'rw+');
        $content = 'some content';
        fwrite($resource, $content);
        // Dont rewind

        $s3 = $this->getClient();
        $input = new PutObjectRequest();
        $input->setBucket('foo')
            ->setKey('bar')
            ->setBody($resource);
        $result = $s3->putObject($input);

        $result->resolve();
        fclose($resource);

        $info = $result->info();
        self::assertEquals(200, $info['status']);

        // Test get object
        $result = $s3->getObject(['Bucket' => 'foo', 'Key' => 'bar']);
        $body = $result->getBody()->getContentAsString();

        self::assertEquals($content, $body);
    }

    private function getClient(): S3Client
    {
        return new S3Client([
            'endpoint' => 'http://localhost:4569',
        ], new NullProvider());
    }
}
