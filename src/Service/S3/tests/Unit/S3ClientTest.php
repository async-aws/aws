<?php

namespace AsyncAws\S3\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\S3\Input\CopyObjectRequest;
use AsyncAws\S3\Input\CreateBucketRequest;
use AsyncAws\S3\Input\DeleteObjectRequest;
use AsyncAws\S3\Input\DeleteObjectsRequest;
use AsyncAws\S3\Input\GetObjectAclRequest;
use AsyncAws\S3\Input\GetObjectRequest;
use AsyncAws\S3\Input\HeadObjectRequest;
use AsyncAws\S3\Input\ListObjectsV2Request;
use AsyncAws\S3\Input\PutObjectAclRequest;
use AsyncAws\S3\Input\PutObjectRequest;
use AsyncAws\S3\Result\CopyObjectOutput;
use AsyncAws\S3\Result\CreateBucketOutput;
use AsyncAws\S3\Result\DeleteObjectOutput;
use AsyncAws\S3\Result\DeleteObjectsOutput;
use AsyncAws\S3\Result\GetObjectAclOutput;
use AsyncAws\S3\Result\GetObjectOutput;
use AsyncAws\S3\Result\HeadObjectOutput;
use AsyncAws\S3\Result\ListObjectsV2Output;
use AsyncAws\S3\Result\PutObjectAclOutput;
use AsyncAws\S3\Result\PutObjectOutput;
use AsyncAws\S3\S3Client;
use AsyncAws\S3\ValueObject\Delete;
use AsyncAws\S3\ValueObject\ObjectIdentifier;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class S3ClientTest extends TestCase
{
    public function testCopyObject(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new CopyObjectRequest([

            'Bucket' => 'change me',

            'CopySource' => 'change me',

            'Key' => 'change me',

        ]);
        $result = $client->CopyObject($input);

        self::assertInstanceOf(CopyObjectOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testCreateBucket(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new CreateBucketRequest([

            'Bucket' => 'change me',

        ]);
        $result = $client->CreateBucket($input);

        self::assertInstanceOf(CreateBucketOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteObject(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new DeleteObjectRequest([
            'Bucket' => 'change me',
            'Key' => 'change me',

        ]);
        $result = $client->DeleteObject($input);

        self::assertInstanceOf(DeleteObjectOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteObjects(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new DeleteObjectsRequest([
            'Bucket' => 'change me',
            'Delete' => new Delete([
                'Objects' => [new ObjectIdentifier([
                    'Key' => 'change me',
                    'VersionId' => 'change me',
                ])],
                'Quiet' => false,
            ]),

        ]);
        $result = $client->DeleteObjects($input);

        self::assertInstanceOf(DeleteObjectsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetObject(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new GetObjectRequest([
            'Bucket' => 'change me',

            'Key' => 'change me',

        ]);
        $result = $client->GetObject($input);

        self::assertInstanceOf(GetObjectOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetObjectAcl(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new GetObjectAclRequest([
            'Bucket' => 'change me',
            'Key' => 'change me',

        ]);
        $result = $client->GetObjectAcl($input);

        self::assertInstanceOf(GetObjectAclOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testHeadObject(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new HeadObjectRequest([
            'Bucket' => 'change me',

            'Key' => 'change me',

        ]);
        $result = $client->HeadObject($input);

        self::assertInstanceOf(HeadObjectOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListObjectsV2(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new ListObjectsV2Request([
            'Bucket' => 'change me',

        ]);
        $result = $client->ListObjectsV2($input);

        self::assertInstanceOf(ListObjectsV2Output::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPutObject(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new PutObjectRequest([

            'Bucket' => 'change me',

            'Key' => 'change me',

        ]);
        $result = $client->PutObject($input);

        self::assertInstanceOf(PutObjectOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPutObjectAcl(): void
    {
        $client = new S3Client([], new NullProvider(), new MockHttpClient());

        $input = new PutObjectAclRequest([

            'Bucket' => 'change me',

            'Key' => 'change me',

        ]);
        $result = $client->PutObjectAcl($input);

        self::assertInstanceOf(PutObjectAclOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
