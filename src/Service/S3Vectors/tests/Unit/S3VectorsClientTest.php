<?php

namespace AsyncAws\S3Vectors\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Input\CreateIndexInput;
use AsyncAws\S3Vectors\Input\CreateVectorBucketInput;
use AsyncAws\S3Vectors\Input\DeleteIndexInput;
use AsyncAws\S3Vectors\Input\DeleteVectorBucketInput;
use AsyncAws\S3Vectors\Input\DeleteVectorBucketPolicyInput;
use AsyncAws\S3Vectors\Input\DeleteVectorsInput;
use AsyncAws\S3Vectors\Input\GetIndexInput;
use AsyncAws\S3Vectors\Input\GetVectorBucketInput;
use AsyncAws\S3Vectors\Input\GetVectorBucketPolicyInput;
use AsyncAws\S3Vectors\Input\ListIndexesInput;
use AsyncAws\S3Vectors\Input\ListTagsForResourceInput;
use AsyncAws\S3Vectors\Input\ListVectorBucketsInput;
use AsyncAws\S3Vectors\Input\PutVectorBucketPolicyInput;
use AsyncAws\S3Vectors\Input\TagResourceInput;
use AsyncAws\S3Vectors\Result\CreateIndexOutput;
use AsyncAws\S3Vectors\Result\CreateVectorBucketOutput;
use AsyncAws\S3Vectors\Result\DeleteIndexOutput;
use AsyncAws\S3Vectors\Result\DeleteVectorBucketOutput;
use AsyncAws\S3Vectors\Result\DeleteVectorBucketPolicyOutput;
use AsyncAws\S3Vectors\Result\DeleteVectorsOutput;
use AsyncAws\S3Vectors\Result\GetIndexOutput;
use AsyncAws\S3Vectors\Result\GetVectorBucketOutput;
use AsyncAws\S3Vectors\Result\GetVectorBucketPolicyOutput;
use AsyncAws\S3Vectors\Result\ListIndexesOutput;
use AsyncAws\S3Vectors\Result\ListTagsForResourceOutput;
use AsyncAws\S3Vectors\Result\ListVectorBucketsOutput;
use AsyncAws\S3Vectors\Result\PutVectorBucketPolicyOutput;
use AsyncAws\S3Vectors\Result\TagResourceOutput;
use AsyncAws\S3Vectors\S3VectorsClient;
use Symfony\Component\HttpClient\MockHttpClient;

class S3VectorsClientTest extends TestCase
{
    public function testCreateIndex(): void
    {
        $client = new S3VectorsClient([], new NullProvider(), new MockHttpClient());

        $input = new CreateIndexInput([
            'indexName' => 'change me',
            'dataType' => 'change me',
            'dimension' => 1337,
            'distanceMetric' => 'change me',
        ]);
        $result = $client->createIndex($input);

        self::assertInstanceOf(CreateIndexOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testCreateVectorBucket(): void
    {
        $client = new S3VectorsClient([], new NullProvider(), new MockHttpClient());

        $input = new CreateVectorBucketInput([
            'vectorBucketName' => 'change me',
        ]);
        $result = $client->createVectorBucket($input);

        self::assertInstanceOf(CreateVectorBucketOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteIndex(): void
    {
        $client = new S3VectorsClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteIndexInput([
        ]);
        $result = $client->deleteIndex($input);

        self::assertInstanceOf(DeleteIndexOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteVectorBucket(): void
    {
        $client = new S3VectorsClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteVectorBucketInput([
        ]);
        $result = $client->deleteVectorBucket($input);

        self::assertInstanceOf(DeleteVectorBucketOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteVectorBucketPolicy(): void
    {
        $client = new S3VectorsClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteVectorBucketPolicyInput([
        ]);
        $result = $client->deleteVectorBucketPolicy($input);

        self::assertInstanceOf(DeleteVectorBucketPolicyOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteVectors(): void
    {
        $client = new S3VectorsClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteVectorsInput([
            'keys' => ['change me'],
        ]);
        $result = $client->deleteVectors($input);

        self::assertInstanceOf(DeleteVectorsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetIndex(): void
    {
        $client = new S3VectorsClient([], new NullProvider(), new MockHttpClient());

        $input = new GetIndexInput([
        ]);
        $result = $client->getIndex($input);

        self::assertInstanceOf(GetIndexOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetVectorBucket(): void
    {
        $client = new S3VectorsClient([], new NullProvider(), new MockHttpClient());

        $input = new GetVectorBucketInput([
        ]);
        $result = $client->getVectorBucket($input);

        self::assertInstanceOf(GetVectorBucketOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetVectorBucketPolicy(): void
    {
        $client = new S3VectorsClient([], new NullProvider(), new MockHttpClient());

        $input = new GetVectorBucketPolicyInput([
        ]);
        $result = $client->getVectorBucketPolicy($input);

        self::assertInstanceOf(GetVectorBucketPolicyOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListIndexes(): void
    {
        $client = new S3VectorsClient([], new NullProvider(), new MockHttpClient());

        $input = new ListIndexesInput([
        ]);
        $result = $client->listIndexes($input);

        self::assertInstanceOf(ListIndexesOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListTagsForResource(): void
    {
        $client = new S3VectorsClient([], new NullProvider(), new MockHttpClient());

        $input = new ListTagsForResourceInput([
            'resourceArn' => 'change me',
        ]);
        $result = $client->listTagsForResource($input);

        self::assertInstanceOf(ListTagsForResourceOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListVectorBuckets(): void
    {
        $client = new S3VectorsClient([], new NullProvider(), new MockHttpClient());

        $input = new ListVectorBucketsInput([
        ]);
        $result = $client->listVectorBuckets($input);

        self::assertInstanceOf(ListVectorBucketsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPutVectorBucketPolicy(): void
    {
        $client = new S3VectorsClient([], new NullProvider(), new MockHttpClient());

        $input = new PutVectorBucketPolicyInput([
            'policy' => 'change me',
        ]);
        $result = $client->putVectorBucketPolicy($input);

        self::assertInstanceOf(PutVectorBucketPolicyOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testTagResource(): void
    {
        $client = new S3VectorsClient([], new NullProvider(), new MockHttpClient());

        $input = new TagResourceInput([
            'resourceArn' => 'change me',
            'tags' => ['change me' => 'change me'],
        ]);
        $result = $client->tagResource($input);

        self::assertInstanceOf(TagResourceOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
