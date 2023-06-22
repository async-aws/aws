<?php

namespace AsyncAws\S3\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Enum\Event;
use AsyncAws\S3\Enum\FilterRuleName;
use AsyncAws\S3\Enum\Permission;
use AsyncAws\S3\Enum\Type;
use AsyncAws\S3\Input\AbortMultipartUploadRequest;
use AsyncAws\S3\Input\CompleteMultipartUploadRequest;
use AsyncAws\S3\Input\CopyObjectRequest;
use AsyncAws\S3\Input\CreateBucketRequest;
use AsyncAws\S3\Input\CreateMultipartUploadRequest;
use AsyncAws\S3\Input\DeleteBucketCorsRequest;
use AsyncAws\S3\Input\DeleteBucketRequest;
use AsyncAws\S3\Input\DeleteObjectRequest;
use AsyncAws\S3\Input\GetBucketCorsRequest;
use AsyncAws\S3\Input\GetBucketEncryptionRequest;
use AsyncAws\S3\Input\GetObjectAclRequest;
use AsyncAws\S3\Input\GetObjectRequest;
use AsyncAws\S3\Input\HeadBucketRequest;
use AsyncAws\S3\Input\HeadObjectRequest;
use AsyncAws\S3\Input\ListBucketsRequest;
use AsyncAws\S3\Input\ListMultipartUploadsRequest;
use AsyncAws\S3\Input\ListObjectsV2Request;
use AsyncAws\S3\Input\ListPartsRequest;
use AsyncAws\S3\Input\PutBucketCorsRequest;
use AsyncAws\S3\Input\PutBucketNotificationConfigurationRequest;
use AsyncAws\S3\Input\PutBucketTaggingRequest;
use AsyncAws\S3\Input\PutObjectAclRequest;
use AsyncAws\S3\Input\PutObjectRequest;
use AsyncAws\S3\Input\UploadPartRequest;
use AsyncAws\S3\Result\PutObjectOutput;
use AsyncAws\S3\S3Client;
use AsyncAws\S3\ValueObject\AccessControlPolicy;
use AsyncAws\S3\ValueObject\AwsObject;
use AsyncAws\S3\ValueObject\Bucket;
use AsyncAws\S3\ValueObject\CommonPrefix;
use AsyncAws\S3\ValueObject\CompletedMultipartUpload;
use AsyncAws\S3\ValueObject\CompletedPart;
use AsyncAws\S3\ValueObject\CORSConfiguration;
use AsyncAws\S3\ValueObject\CORSRule;
use AsyncAws\S3\ValueObject\FilterRule;
use AsyncAws\S3\ValueObject\Grant;
use AsyncAws\S3\ValueObject\Grantee;
use AsyncAws\S3\ValueObject\LambdaFunctionConfiguration;
use AsyncAws\S3\ValueObject\NotificationConfiguration;
use AsyncAws\S3\ValueObject\NotificationConfigurationFilter;
use AsyncAws\S3\ValueObject\Owner;
use AsyncAws\S3\ValueObject\QueueConfiguration;
use AsyncAws\S3\ValueObject\S3KeyFilter;
use AsyncAws\S3\ValueObject\Tag;
use AsyncAws\S3\ValueObject\Tagging;
use AsyncAws\S3\ValueObject\TopicConfiguration;

class S3ClientTest extends TestCase
{
    public function testAbortMultipartUpload(): void
    {
        $client = $this->getClient();

        $input = new AbortMultipartUploadRequest([
            'Bucket' => 'foo',
            'Key' => 'Bar',
            'UploadId' => '123',
        ]);
        $result = $client->AbortMultipartUpload($input);

        $result->resolve();

        self::assertEquals(204, $result->info()['status']);
    }

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

    public function testBucketExists(): void
    {
        $client = $this->getClient();

        $client->CreateBucket(['Bucket' => 'foo'])->resolve();

        $input = new HeadBucketRequest([
            'Bucket' => 'foo',
        ]);

        self::assertTrue($client->bucketExists($input)->isSuccess());
        self::assertFalse($client->bucketExists(['Bucket' => 'does-not-exists'])->isSuccess());
    }

    public function testBucketNotExists(): void
    {
        $client = $this->getClient();

        $client->CreateBucket(['Bucket' => 'foo'])->resolve();

        $input = new HeadBucketRequest([
            'Bucket' => 'foo',
        ]);

        self::assertFalse($client->bucketNotExists($input)->isSuccess());
        self::assertTrue($client->bucketNotExists(['Bucket' => 'does-not-exists'])->isSuccess());
    }

    public function testCompleteMultipartUpload(): void
    {
        self::markTestSkipped('Not supported on Docker');
        $client = $this->getClient();

        $input = new CompleteMultipartUploadRequest([
            'Bucket' => 'foo',
            'Key' => 'bar',
            'MultipartUpload' => new CompletedMultipartUpload([
                'Parts' => [new CompletedPart([
                    'ETag' => 'change me',
                    'PartNumber' => 1337,
                ])],
            ]),
            'UploadId' => 'change me',
        ]);
        $result = $client->CompleteMultipartUpload($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getLocation());
        self::assertSame('changeIt', $result->getBucket());
        self::assertSame('changeIt', $result->getKey());
        self::assertSame('changeIt', $result->getExpiration());
        self::assertSame('changeIt', $result->getETag());
        self::assertSame('changeIt', $result->getServerSideEncryption());
        self::assertSame('changeIt', $result->getVersionId());
        self::assertSame('changeIt', $result->getSSEKMSKeyId());
        self::assertSame('changeIt', $result->getRequestCharged());
    }

    public function testCopyObject(): void
    {
        $client = $this->getClient();
        $client->putObject([
            'Bucket' => 'foo',
            'Key' => 'bar',
            'Body' => 'content',
        ])->resolve();

        $input = new CopyObjectRequest([
            'Bucket' => 'foo',
            'ContentType' => 'text/plain',
            'CopySource' => 'foo/bar',
            'Key' => 'baz',
        ]);
        $result = $client->CopyObject($input);

        $result->resolve();

        // fetch copied object
        $result = $client->getObject([
            'Bucket' => 'foo',
            'Key' => 'baz',
        ]);
        self::assertEquals('content', $result->getBody()->getContentAsString());
    }

    public function testCreateBucket(): void
    {
        $client = $this->getClient();

        $input = new CreateBucketRequest([
            'Bucket' => 'qux',
        ]);
        $result = $client->CreateBucket($input);

        $result->resolve();

        // Because of FakeS3, response is null
        self::assertNull($result->getLocation());
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

    public function testCreateMultipartUpload(): void
    {
        $client = $this->getClient();

        $input = new CreateMultipartUploadRequest([
            'Bucket' => 'foo',
            'Key' => 'bar',
        ]);
        $result = $client->CreateMultipartUpload($input);

        $result->resolve();

        self::assertSame('foo', $result->getBucket());
        self::assertNotNull($result->getUploadId());
    }

    public function testDeleteBucket(): void
    {
        $client = $this->getClient();
        $client->CreateBucket(['Bucket' => 'foo-exist']);

        self::assertTrue($client->bucketExists(new HeadBucketRequest([
            'Bucket' => 'foo-exist',
        ]))->isSuccess());

        $client->DeleteBucket(new DeleteBucketRequest([
            'Bucket' => 'foo-exist',
        ]));

        self::assertFalse($client->bucketExists(new HeadBucketRequest([
            'Bucket' => 'foo-exist',
        ]))->isSuccess());
    }

    public function testDeleteBucketCors(): void
    {
        self::markTestSkipped('The S3 Docker image does not implement DeleteBucketCors.');
        $client = $this->getClient();
        $bucket = 'foo';

        $input = new DeleteBucketCorsRequest([
            'Bucket' => $bucket,
        ]);
        $result = $client->DeleteBucketCors($input);

        $result->resolve();
    }

    public function testDeleteObject(): void
    {
        $client = $this->getClient();

        $client->putObject([
            'Bucket' => 'foo',
            'Key' => 'bar',
        ])->resolve();

        $input = new DeleteObjectRequest([
            'Bucket' => 'foo',
            'Key' => 'bar',
        ]);
        $result = $client->DeleteObject($input);

        $result->resolve();

        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('HTTP 404 returned for "http://localhost:4569/foo/bar".');

        $client->getObject([
            'Bucket' => 'foo',
            'Key' => 'bar',
        ]) - resolve();
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

    public function testGetBucketCors(): void
    {
        self::markTestSkipped('The S3 Docker image does not implement GetBucketCors.');
        $client = $this->getClient();
        $bucket = 'foo';

        $input = new GetBucketCorsRequest([
            'Bucket' => $bucket,
        ]);
        $result = $client->GetBucketCors($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getCORSRules());
    }

    public function testGetBucketEncryption(): void
    {
        self::markTestSkipped('The S3 Docker image does not implement GetBucketEncryption.');
        $client = $this->getClient();

        $input = new GetBucketEncryptionRequest([
            'Bucket' => 'change me',
            'ExpectedBucketOwner' => 'change me',
        ]);
        $result = $client->getBucketEncryption($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getServerSideEncryptionConfiguration());
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
        $client = $this->getClient();
        $client->putObject([
            'Bucket' => 'foo',
            'Key' => 'bar',
            'Body' => 'content',
            'ContentType' => 'image/jpg',
        ])->resolve();

        $result = $client->getObject([
            'Bucket' => 'foo',
            'Key' => 'bar',
        ]);
        self::assertEquals('content', $result->getBody()->getContentAsString());
        self::assertEquals('bytes', $result->getAcceptRanges());
        self::assertSame('7', $result->getContentLength());
        self::assertEquals('"9a0364b9e99bb480dd25e1f0284c8555"', $result->getETag());
        self::assertEquals('image/jpg', $result->getContentType());
    }

    public function testGetObjectAcl(): void
    {
        $client = $this->getClient();
        $client->putObject([
            'Bucket' => 'foo',
            'Key' => 'bar',
            'Body' => 'content',
        ])->resolve();

        $input = new GetObjectAclRequest([
            'Bucket' => 'foo',
            'Key' => 'bar',
        ]);
        $result = $client->GetObjectAcl($input);

        $result->resolve();

        self::assertSame('You', $result->getOwner()->getDisplayName());
        self::assertCount(1, $result->getGrants());
        self::assertSame('FULL_CONTROL', $result->getGrants()[0]->getPermission());
        self::assertSame('You', $result->getGrants()[0]->getGrantee()->getDisplayName());
        self::assertSame('CanonicalUser', $result->getGrants()[0]->getGrantee()->getType());
    }

    public function testGetObjectConsistent(): void
    {
        $client = $this->getClient();
        $client->putObject([
            'Bucket' => 'foo',
            'Key' => 'bar',
            'Body' => 'content',
            'ContentType' => 'image/jpg',
        ])->resolve();

        $result = $client->getObject([
            'Bucket' => 'foo',
            'Key' => 'bar',
        ]);
        self::assertEquals('content', $result->getBody()->getContentAsString());
        // calling it twice to ensure consitency
        self::assertEquals('content', $result->getBody()->getContentAsString());
        self::assertEquals('content', stream_get_contents($result->getBody()->getContentAsResource()));
        self::assertEquals('content', implode('', iterator_to_array($result->getBody()->getChunks())));
        // test it twice to check both getChunk and fallback
        self::assertEquals('content', implode('', iterator_to_array($result->getBody()->getChunks())));
    }

    public function testHeadObject(): void
    {
        $client = $this->getClient();
        $client->putObject([
            'Bucket' => 'foo',
            'Key' => 'bar',
            'Body' => 'content',
        ])->resolve();

        $input = new HeadObjectRequest([
            'Bucket' => 'foo',
            'Key' => 'bar',
        ]);
        $result = $client->HeadObject($input);

        $result->resolve();

        self::assertSame('7', $result->getContentLength());
        self::assertEquals('"9a0364b9e99bb480dd25e1f0284c8555"', $result->getETag());
    }

    public function testKeyExists(): void
    {
        $client = $this->getClient();

        $client->putObject([
            'Bucket' => 'foo',
            'Key' => 'bar',
            'Body' => 'content',
        ])->resolve();

        $input = new HeadObjectRequest([
            'Bucket' => 'foo',
            'Key' => 'bar',
        ]);

        self::assertTrue($client->objectExists($input)->isSuccess());
        self::assertFalse($client->objectExists(['Bucket' => 'foo', 'Key' => 'does-not-exists'])->isSuccess());
    }

    public function testKeyNotExists(): void
    {
        $client = $this->getClient();

        $client->putObject([
            'Bucket' => 'foo',
            'Key' => 'bar',
            'Body' => 'content',
        ])->resolve();

        $input = new HeadObjectRequest([
            'Bucket' => 'foo',
            'Key' => 'bar',
        ]);

        self::assertFalse($client->objectNotExists($input)->isSuccess());
        self::assertTrue($client->objectNotExists(['Bucket' => 'foo', 'Key' => 'does-not-exists'])->isSuccess());
    }

    public function testKeyWithSpecialChars(): void
    {
        $client = $this->getClient();

        $client->CreateBucket(['Bucket' => 'foo#pound'])->resolve();

        $result = $client->PutObject([
            'Body' => 'content',
            'Bucket' => 'foo#pound',
            'Key' => 'bar#pound/baz#pound',
        ]);

        self::assertEquals('"9a0364b9e99bb480dd25e1f0284c8555"', $result->getETag());

        $result = $client->GetObject([
            'Bucket' => 'foo#pound',
            'Key' => 'bar#pound/baz#pound',
        ]);
        self::assertEquals('content', $result->getBody()->getContentAsString());

        $result = $client->listObjectsV2([
            'Bucket' => 'foo#pound',
            'Delimiter' => '/',
        ]);
        self::assertEquals(['bar#pound/'], array_map(function (CommonPrefix $prefix) {return $prefix->getPrefix(); }, iterator_to_array($result->getCommonPrefixes())));

        $result = $client->listObjectsV2([
            'Bucket' => 'foo#pound',
            'Prefix' => 'bar#pound/',
        ]);

        self::assertEquals(['bar#pound/baz#pound'], array_map(function (AwsObject $prefix) {return $prefix->getKey(); }, iterator_to_array($result->getContents())));
    }

    public function testListBuckets(): void
    {
        $client = $this->getClient();
        $client->CreateBucket(['Bucket' => 'test-list'])->resolve();

        $input = new ListBucketsRequest([]);
        $result = $client->listBuckets($input);

        $result->resolve();

        self::assertSame('FakeS3', $result->getOwner()->getDisplayName());
        $buckets = array_map(static function (Bucket $bucket): string {
            return $bucket->getName();
        }, $result->getBuckets());

        self::assertContains('test-list', $buckets);
    }

    public function testListMultipartUploads(): void
    {
        self::markTestSkipped('Not really supported on Docker');
        $client = $this->getClient();

        $input = new ListMultipartUploadsRequest([
            'Bucket' => 'foo',
            'Delimiter' => '/',
            'MaxUploads' => 2,
        ]);
        $result = $client->ListMultipartUploads($input);

        $result->resolve();

        self::assertSame('foo', $result->getBucket());
        self::assertSame('changeIt', $result->getKeyMarker());
        self::assertSame('changeIt', $result->getUploadIdMarker());
        self::assertSame('changeIt', $result->getNextKeyMarker());
        self::assertSame('changeIt', $result->getPrefix());
        self::assertSame('changeIt', $result->getDelimiter());
        self::assertSame('changeIt', $result->getNextUploadIdMarker());
        self::assertSame(1337, $result->getMaxUploads());
        self::assertFalse($result->getIsTruncated());
        // self::assertTODO(expected, $result->getUploads());
        // self::assertTODO(expected, $result->getCommonPrefixes());
        self::assertSame('changeIt', $result->getEncodingType());
    }

    public function testListObjectsV2()
    {
        $s3 = $this->getClient();

        $requests = [];
        for ($i = 0; $i < 5; ++$i) {
            $requests[] = $s3->putObject(['Bucket' => 'foo', 'Key' => 'list/content-' . $i, 'Body' => 'test']);
            $requests[] = $s3->putObject(['Bucket' => 'foo', 'Key' => 'list/prefix-' . $i . '/file']);
        }
        array_walk($requests, function (PutObjectOutput $response) {
            $response->resolve();
        });

        self::markTestSkipped('The S3 image does not implement Pagination. https://github.com/jubos/fake-s3/issues/223');

        $input = (new ListObjectsV2Request())
        ->setBucket('foo')
        ->setPrefix('list/')
        // ->setMaxKeys(2) // pagination is not implemented
        ->setDelimiter('/')
        ;

        $result = $s3->listObjectsV2($input);

        self::assertCount(10, $result);
        self::assertCount(5, $result->getCommonPrefixes());
        self::assertCount(5, $result->getContents());

        $files = array_map(function (AwsObject $content) {
            return $content->getKey();
        }, iterator_to_array($result->getContents()));
        $prefixes = array_map(function (CommonPrefix $prefix) {
            return $prefix->getPrefix();
        }, iterator_to_array($result->getCommonPrefixes()));

        self::assertContains('list/prefix-1/', $prefixes);
        self::assertContains('list/content-2', $files);
    }

    public function testListParts(): void
    {
        self::markTestSkipped('');
        $client = $this->getClient();

        $input = new ListPartsRequest([
            'Bucket' => 'change me',
            'Key' => 'change me',
            'MaxParts' => 1337,
            'PartNumberMarker' => 1337,
            'UploadId' => 'change me',
            'RequestPayer' => 'change me',
        ]);
        $result = $client->ListParts($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getAbortDate());
        self::assertSame('changeIt', $result->getAbortRuleId());
        self::assertSame('changeIt', $result->getBucket());
        self::assertSame('changeIt', $result->getKey());
        self::assertSame('changeIt', $result->getUploadId());
        self::assertSame(1337, $result->getPartNumberMarker());
        self::assertSame(1337, $result->getNextPartNumberMarker());
        self::assertSame(1337, $result->getMaxParts());
        self::assertFalse($result->getIsTruncated());
        // self::assertTODO(expected, $result->getParts());
        // self::assertTODO(expected, $result->getInitiator());
        // self::assertTODO(expected, $result->getOwner());
        self::assertSame('changeIt', $result->getStorageClass());
        self::assertSame('changeIt', $result->getRequestCharged());
    }

    public function testPutBucketCors(): void
    {
        $client = $this->getClient();

        $input = new PutBucketCorsRequest([
            'Bucket' => 'bucket-name',
            'CORSConfiguration' => new CORSConfiguration([
                'CORSRules' => [new CORSRule([
                    'AllowedHeaders' => ['*'],
                    'AllowedMethods' => ['GET', 'PUT'],
                    'AllowedOrigins' => ['test.example.com'],
                    'ExposeHeaders' => [],
                    'MaxAgeSeconds' => 1337,
                ])],
            ]),
            'ContentMD5' => 'change me',
            'ExpectedBucketOwner' => 'change me',
        ]);
        $result = $client->PutBucketCors($input);

        self::assertTrue($result->resolve());

        $info = $result->info();
        self::assertEquals(200, $info['status']);
    }

    public function testPutBucketNotificationConfiguration(): void
    {
        $client = $this->getClient();

        $input = new PutBucketNotificationConfigurationRequest([
            'Bucket' => 'bucket-name',
            'NotificationConfiguration' => new NotificationConfiguration([
                'TopicConfigurations' => [
                    new TopicConfiguration([
                        'Id' => 'TopicId',
                        'TopicArn' => 'arn:topic',
                        'Events' => [Event::S3_OBJECT_CREATED_ALL],
                        'Filter' => new NotificationConfigurationFilter([
                            'Key' => new S3KeyFilter([
                                'FilterRules' => [
                                    new FilterRule([
                                        'Name' => FilterRuleName::PREFIX,
                                        'Value' => 'images/',
                                    ]),
                                ],
                            ]),
                        ]),
                    ]),
                ],
                'QueueConfigurations' => [
                    new QueueConfiguration([
                        'Id' => 'QueueId',
                        'QueueArn' => 'arn:queue',
                        'Events' => [Event::S3_OBJECT_CREATED_ALL],
                        'Filter' => new NotificationConfigurationFilter([
                            'Key' => new S3KeyFilter([
                                'FilterRules' => [new FilterRule([
                                    'Name' => FilterRuleName::PREFIX,
                                    'Value' => 'pdf/',
                                ])],
                            ]),
                        ]),
                    ]),
                ],
                'LambdaFunctionConfigurations' => [
                    new LambdaFunctionConfiguration([
                        'Id' => 'LambdaId',
                        'LambdaFunctionArn' => 'arn:lambda',
                        'Events' => [Event::S3_OBJECT_CREATED_ALL],
                        'Filter' => new NotificationConfigurationFilter([
                            'Key' => new S3KeyFilter([
                                'FilterRules' => [
                                    new FilterRule([
                                        'Name' => FilterRuleName::SUFFIX,
                                        'Value' => '.jpg',
                                    ]),
                                ],
                            ]),
                        ]),
                    ]),
                ],
            ]),
            'ExpectedBucketOwner' => 'bucket-name',
        ]);
        $result = $client->PutBucketNotificationConfiguration($input);

        self::assertTrue($result->resolve());

        $info = $result->info();
        self::assertEquals(200, $info['status']);
    }

    public function testPutBucketTagging(): void
    {
        $client = $this->getClient();

        $input = new PutBucketTaggingRequest([
            'Bucket' => 'my-website-assets-bucket',
            'ContentMD5' => 'NDhmNGNlNzhjZDAzODJlZjY1YTFmNjQzYjMxNmExZDg1YzM0MzZmNzUzNTVhNDBmYzFmOWE2Y2FjNTkyYWYxYQ==',
            'ChecksumAlgorithm' => 'SHA256',
            'Tagging' => new Tagging([
                'TagSet' => [
                    new Tag([
                        'Key' => 'environment',
                        'Value' => 'production',
                    ]),
                    new Tag([
                        'Key' => 'project-name',
                        'Value' => 'unicorn',
                    ]),
                ],
            ]),
            'ExpectedBucketOwner' => '0123456789',
        ]);
        $result = $client->putBucketTagging($input);

        $result->resolve();

        self::assertSame(200, $result->info()['response']->getStatusCode());
        self::assertSame('', $result->info()['response']->getContent());
    }

    public function testPutObject(): void
    {
        $client = $this->getClient();

        $input = new PutObjectRequest([
            'Body' => 'content',
            'Bucket' => 'foo',
            'Key' => 'bar',
        ]);
        $result = $client->PutObject($input);

        self::assertEquals('"9a0364b9e99bb480dd25e1f0284c8555"', $result->getETag());
    }

    public function testPutObjectAcl(): void
    {
        $client = $this->getClient();

        $client->PutObject([
            'Body' => 'content',
            'Bucket' => 'foo',
            'Key' => 'bar',
        ])->resolve();

        $input = new PutObjectAclRequest([
            'AccessControlPolicy' => new AccessControlPolicy([
                'Grants' => [new Grant([
                    'Grantee' => new Grantee([
                        'DisplayName' => 'me',
                        'Type' => Type::CANONICAL_USER,
                    ]),
                    'Permission' => Permission::FULL_CONTROL,
                ])],
                'Owner' => new Owner([
                    'DisplayName' => 'me',
                ]),
            ]),
            'Bucket' => 'foo',
            'Key' => 'bar',
        ]);
        $result = $client->PutObjectAcl($input);

        $result->resolve();

        // Not flly Implemented by fakeS3
        self::assertNull($result->getRequestCharged());
    }

    public function testUploadFromClosure()
    {
        $parts = ['some ', 'content'];
        $content = implode('', $parts);
        $index = 0;
        $closure = \Closure::fromCallable(static function () use ($parts, &$index) {
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
        $resource = fopen('php://temp', 'rw+');
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

    public function testUploadPart(): void
    {
        $client = $this->getClient();

        $input = new UploadPartRequest([
            'Body' => 'movie',
            'Bucket' => 'foo',
            'Key' => 'bar',
            'PartNumber' => 2,
            'UploadId' => '123',
        ]);
        $result = $client->UploadPart($input);

        $result->resolve();

        self::assertEquals(200, $result->info()['status']);
    }

    private function getClient(): S3Client
    {
        return new S3Client([
            'endpoint' => 'http://localhost:4569',
            'pathStyleEndpoint' => true,
        ], new NullProvider());
    }
}
