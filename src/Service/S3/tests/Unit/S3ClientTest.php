<?php

namespace AsyncAws\S3\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Result;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\AbortMultipartUploadRequest;
use AsyncAws\S3\Input\CompleteMultipartUploadRequest;
use AsyncAws\S3\Input\CopyObjectRequest;
use AsyncAws\S3\Input\CreateBucketRequest;
use AsyncAws\S3\Input\CreateMultipartUploadRequest;
use AsyncAws\S3\Input\DeleteBucketCorsRequest;
use AsyncAws\S3\Input\DeleteBucketRequest;
use AsyncAws\S3\Input\DeleteObjectRequest;
use AsyncAws\S3\Input\DeleteObjectsRequest;
use AsyncAws\S3\Input\GetBucketCorsRequest;
use AsyncAws\S3\Input\GetBucketEncryptionRequest;
use AsyncAws\S3\Input\GetObjectAclRequest;
use AsyncAws\S3\Input\GetObjectRequest;
use AsyncAws\S3\Input\HeadObjectRequest;
use AsyncAws\S3\Input\ListBucketsRequest;
use AsyncAws\S3\Input\ListMultipartUploadsRequest;
use AsyncAws\S3\Input\ListObjectsV2Request;
use AsyncAws\S3\Input\ListPartsRequest;
use AsyncAws\S3\Input\PutBucketCorsRequest;
use AsyncAws\S3\Input\PutBucketNotificationConfigurationRequest;
use AsyncAws\S3\Input\PutObjectAclRequest;
use AsyncAws\S3\Input\PutObjectRequest;
use AsyncAws\S3\Input\UploadPartRequest;
use AsyncAws\S3\Result\AbortMultipartUploadOutput;
use AsyncAws\S3\Result\CompleteMultipartUploadOutput;
use AsyncAws\S3\Result\CopyObjectOutput;
use AsyncAws\S3\Result\CreateBucketOutput;
use AsyncAws\S3\Result\CreateMultipartUploadOutput;
use AsyncAws\S3\Result\DeleteObjectOutput;
use AsyncAws\S3\Result\DeleteObjectsOutput;
use AsyncAws\S3\Result\GetBucketCorsOutput;
use AsyncAws\S3\Result\GetBucketEncryptionOutput;
use AsyncAws\S3\Result\GetObjectAclOutput;
use AsyncAws\S3\Result\GetObjectOutput;
use AsyncAws\S3\Result\HeadObjectOutput;
use AsyncAws\S3\Result\ListBucketsOutput;
use AsyncAws\S3\Result\ListMultipartUploadsOutput;
use AsyncAws\S3\Result\ListObjectsV2Output;
use AsyncAws\S3\Result\ListPartsOutput;
use AsyncAws\S3\Result\PutObjectAclOutput;
use AsyncAws\S3\Result\PutObjectOutput;
use AsyncAws\S3\Result\UploadPartOutput;
use AsyncAws\S3\S3Client;
use AsyncAws\S3\ValueObject\CORSConfiguration;
use AsyncAws\S3\ValueObject\CORSRule;
use AsyncAws\S3\ValueObject\Delete;
use AsyncAws\S3\ValueObject\FilterRule;
use AsyncAws\S3\ValueObject\NotificationConfiguration;
use AsyncAws\S3\ValueObject\NotificationConfigurationFilter;
use AsyncAws\S3\ValueObject\ObjectIdentifier;
use AsyncAws\S3\ValueObject\S3KeyFilter;
use AsyncAws\S3\ValueObject\TopicConfiguration;
use Symfony\Component\HttpClient\MockHttpClient;

class S3ClientTest extends TestCase
{
    public function testAbortMultipartUpload(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new AbortMultipartUploadRequest([
            'Bucket' => 'example-bucket',
            'Key' => 'file.png',
            'UploadId' => '123',
        ]);
        $result = $client->AbortMultipartUpload($input);

        self::assertInstanceOf(AbortMultipartUploadOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testBucketToHost(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());
        self::assertSame('https://foo.s3.amazonaws.com/', $client->presign(new CreateBucketRequest(['Bucket' => 'foo'])));
        self::assertSame('https://61515-bar.s3.amazonaws.com/', $client->presign(new CreateBucketRequest(['Bucket' => '61515-bar'])));
        // invalid bucket names
        self::assertSame('https://s3.amazonaws.com/foo.bar', $client->presign(new CreateBucketRequest(['Bucket' => 'foo.bar'])));
        self::assertSame('https://s3.amazonaws.com/foo%20bar', $client->presign(new CreateBucketRequest(['Bucket' => 'foo bar'])));
        self::assertSame('https://s3.amazonaws.com/127.0.0.1', $client->presign(new CreateBucketRequest(['Bucket' => '127.0.0.1'])));

        $client = new S3Client(['endpoint' => 'http://127.0.0.1'], new NullProvider(), new MockHttpClient());
        self::assertSame('http://127.0.0.1/foo', $client->presign(new CreateBucketRequest(['Bucket' => 'foo'])));

        $client = new S3Client(['endpoint' => 'http://custom.com', 'pathStyleEndpoint' => true], new NullProvider(), new MockHttpClient());
        self::assertSame('http://custom.com/foo', $client->presign(new CreateBucketRequest(['Bucket' => 'foo'])));

        $client = new S3Client(['endpoint' => 'http://custom.com'], new NullProvider(), new MockHttpClient());
        self::assertSame('http://foo.custom.com/', $client->presign(new CreateBucketRequest(['Bucket' => 'foo'])));
    }

    public function testCompleteMultipartUpload(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new CompleteMultipartUploadRequest([
            'Bucket' => 'example-bucket',
            'Key' => 'file.png',
            'UploadId' => '123',
        ]);
        $result = $client->CompleteMultipartUpload($input);

        self::assertInstanceOf(CompleteMultipartUploadOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testCopyObject(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new CopyObjectRequest([
            'Bucket' => 'example-bucket',
            'CopySource' => 'change me',
            'Key' => 'file.png',
        ]);
        $result = $client->CopyObject($input);

        self::assertInstanceOf(CopyObjectOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testCreateBucket(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new CreateBucketRequest([
            'Bucket' => 'example-bucket',
        ]);
        $result = $client->CreateBucket($input);

        self::assertInstanceOf(CreateBucketOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testCreateMultipartUpload(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new CreateMultipartUploadRequest([
            'Bucket' => 'example-bucket',
            'Key' => 'file.png',
        ]);
        $result = $client->CreateMultipartUpload($input);

        self::assertInstanceOf(CreateMultipartUploadOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    /**
     * Make sure we can use custom endpoints.
     */
    public function testCustomEndpoint()
    {
        $callback = function ($method, $url, $options) {
            self::assertEquals('PUT', $method);
            self::assertEquals('https://fra1.digitaloceanspaces.com/my_bucket/image/cat.jpg', $url);

            return new SimpleMockedResponse();
        };

        $s3 = new S3Client([
            'endpoint' => 'https://fra1.digitaloceanspaces.com',
            'pathStyleEndpoint' => true,
        ], new NullProvider(), new MockHttpClient($callback));

        $result = $s3->putObject([
            'Bucket' => 'my_bucket',
            'Key' => 'image/cat.jpg',
        ]);

        $result->resolve();
        $info = $result->info();
        self::assertEquals(200, $info['status']);
    }

    public function testDeleteBucket(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new DeleteBucketRequest([
            'Bucket' => 'my_bucket',
        ]);
        $result = $client->DeleteBucket($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteBucketCors(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new DeleteBucketCorsRequest([
            'Bucket' => 'example-bucket',
        ]);
        $result = $client->DeleteBucketCors($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteObject(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new DeleteObjectRequest([
            'Bucket' => 'example-bucket',
            'Key' => 'file.png',
        ]);
        $result = $client->DeleteObject($input);

        self::assertInstanceOf(DeleteObjectOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteObjects(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new DeleteObjectsRequest([
            'Bucket' => 'example-bucket',
            'Delete' => new Delete([
                'Objects' => [new ObjectIdentifier([
                    'Key' => 'file.png',
                    'VersionId' => 'change me',
                ])],
                'Quiet' => false,
            ]),
        ]);
        $result = $client->DeleteObjects($input);

        self::assertInstanceOf(DeleteObjectsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetBucketCors(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new GetBucketCorsRequest([
            'Bucket' => 'example-bucket',
        ]);
        $result = $client->GetBucketCors($input);

        self::assertInstanceOf(GetBucketCorsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetBucketEncryption(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new GetBucketEncryptionRequest([
            'Bucket' => 'example-bucket',
        ]);
        $result = $client->getBucketEncryption($input);

        self::assertInstanceOf(GetBucketEncryptionOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetObject(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new GetObjectRequest([
            'Bucket' => 'example-bucket',
            'Key' => 'file.png',
        ]);
        $result = $client->GetObject($input);

        self::assertInstanceOf(GetObjectOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetObjectAcl(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new GetObjectAclRequest([
            'Bucket' => 'example-bucket',
            'Key' => 'file.png',
        ]);
        $result = $client->GetObjectAcl($input);

        self::assertInstanceOf(GetObjectAclOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testHeadObject(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new HeadObjectRequest([
            'Bucket' => 'example-bucket',
            'Key' => 'file.png',
        ]);
        $result = $client->HeadObject($input);

        self::assertInstanceOf(HeadObjectOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListBuckets(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new ListBucketsRequest([]);
        $result = $client->listBuckets($input);

        self::assertInstanceOf(ListBucketsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListMultipartUploads(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new ListMultipartUploadsRequest([
            'Bucket' => 'example-bucket',
        ]);
        $result = $client->ListMultipartUploads($input);

        self::assertInstanceOf(ListMultipartUploadsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListObjectsV2(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new ListObjectsV2Request([
            'Bucket' => 'example-bucket',
        ]);
        $result = $client->ListObjectsV2($input);

        self::assertInstanceOf(ListObjectsV2Output::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListParts(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new ListPartsRequest([
            'Bucket' => 'example-bucket',
            'Key' => 'file.png',
            'UploadId' => '123',
        ]);
        $result = $client->ListParts($input);

        self::assertInstanceOf(ListPartsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPutBucketCors(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new PutBucketCorsRequest([
            'Bucket' => 'bucket-name',
            'CORSConfiguration' => new CORSConfiguration([
                'CORSRules' => [new CORSRule([
                    'AllowedHeaders' => ['*'],
                    'AllowedMethods' => ['GET', 'PUT'],
                    'AllowedOrigins' => ['test.example.com'],
                ])],
            ]),
        ]);
        $result = $client->PutBucketCors($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPutBucketNotificationConfiguration(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new PutBucketNotificationConfigurationRequest([
            'Bucket' => 'change me',
            'NotificationConfiguration' => new NotificationConfiguration([
                'TopicConfigurations' => [
                    new TopicConfiguration([
                        'Id' => 'change me',
                        'TopicArn' => 'change me',
                        'Events' => ['s3:ObjectCreated:*'],
                        'Filter' => new NotificationConfigurationFilter([
                            'Key' => new S3KeyFilter([
                                'FilterRules' => [
                                    new FilterRule([
                                        'Name' => 'prefix',
                                        'Value' => 'change me',
                                    ]),
                                ],
                            ]),
                        ]),
                    ]),
                ],
            ]),
        ]);
        $result = $client->PutBucketNotificationConfiguration($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPutObject(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new PutObjectRequest([
            'Bucket' => 'example-bucket',
            'Key' => 'file.png',
        ]);
        $result = $client->PutObject($input);

        self::assertInstanceOf(PutObjectOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPutObjectAcl(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new PutObjectAclRequest([
            'Bucket' => 'example-bucket',
            'Key' => 'file.png',
        ]);
        $result = $client->PutObjectAcl($input);

        self::assertInstanceOf(PutObjectAclOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testUploadPart(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new UploadPartRequest([
            'Bucket' => 'example-bucket',
            'Key' => 'file.png',
            'PartNumber' => 1337,
            'UploadId' => '123',
        ]);
        $result = $client->UploadPart($input);

        self::assertInstanceOf(UploadPartOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
