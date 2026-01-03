<?php

namespace AsyncAws\S3Vectors\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Input\CreateIndexInput;
use AsyncAws\S3Vectors\Input\CreateVectorBucketInput;
use AsyncAws\S3Vectors\Input\DeleteIndexInput;
use AsyncAws\S3Vectors\Input\DeleteVectorBucketInput;
use AsyncAws\S3Vectors\Input\DeleteVectorsInput;
use AsyncAws\S3Vectors\Input\GetIndexInput;
use AsyncAws\S3Vectors\Input\GetVectorBucketInput;
use AsyncAws\S3Vectors\Input\GetVectorsInput;
use AsyncAws\S3Vectors\Input\ListIndexesInput;
use AsyncAws\S3Vectors\Input\ListVectorBucketsInput;
use AsyncAws\S3Vectors\Input\ListVectorsInput;
use AsyncAws\S3Vectors\Input\PutVectorsInput;
use AsyncAws\S3Vectors\Input\QueryVectorsInput;
use AsyncAws\S3Vectors\Result\CreateIndexOutput;
use AsyncAws\S3Vectors\Result\CreateVectorBucketOutput;
use AsyncAws\S3Vectors\Result\DeleteIndexOutput;
use AsyncAws\S3Vectors\Result\DeleteVectorBucketOutput;
use AsyncAws\S3Vectors\Result\DeleteVectorsOutput;
use AsyncAws\S3Vectors\Result\GetIndexOutput;
use AsyncAws\S3Vectors\Result\GetVectorBucketOutput;
use AsyncAws\S3Vectors\Result\GetVectorsOutput;
use AsyncAws\S3Vectors\Result\ListIndexesOutput;
use AsyncAws\S3Vectors\Result\ListVectorBucketsOutput;
use AsyncAws\S3Vectors\Result\ListVectorsOutput;
use AsyncAws\S3Vectors\Result\PutVectorsOutput;
use AsyncAws\S3Vectors\Result\QueryVectorsOutput;
use AsyncAws\S3Vectors\S3VectorsClient;
use AsyncAws\S3Vectors\ValueObject\PutInputVector;
use AsyncAws\S3Vectors\ValueObject\VectorData;
use Symfony\Component\HttpClient\MockHttpClient;

class S3VectorsClientTest extends TestCase
{
    public function testCreateIndex(): void
    {
        $client = new S3VectorsClient([], new NullProvider(), new MockHttpClient());

        $input = new CreateIndexInput([
            'indexName' => 'change me',
            'dataType' => \AsyncAws\S3Vectors\Enum\DataType::FLOAT_32,
            'dimension' => 1337,
            'distanceMetric' => \AsyncAws\S3Vectors\Enum\DistanceMetric::COSINE,
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

    public function testGetVectors(): void
    {
        $client = new S3VectorsClient([], new NullProvider(), new MockHttpClient());

        $input = new GetVectorsInput([
            'keys' => ['change me'],
        ]);
        $result = $client->getVectors($input);

        self::assertInstanceOf(GetVectorsOutput::class, $result);
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

    public function testListVectorBuckets(): void
    {
        $client = new S3VectorsClient([], new NullProvider(), new MockHttpClient());

        $input = new ListVectorBucketsInput([
        ]);
        $result = $client->listVectorBuckets($input);

        self::assertInstanceOf(ListVectorBucketsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListVectors(): void
    {
        $client = new S3VectorsClient([], new NullProvider(), new MockHttpClient());

        $input = new ListVectorsInput([
        ]);
        $result = $client->listVectors($input);

        self::assertInstanceOf(ListVectorsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPutVectors(): void
    {
        $client = new S3VectorsClient([], new NullProvider(), new MockHttpClient());

        $input = new PutVectorsInput([
            'vectors' => [new PutInputVector([
                'key' => 'change me',
                'data' => new VectorData([
                ]),
            ])],
        ]);
        $result = $client->putVectors($input);

        self::assertInstanceOf(PutVectorsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testQueryVectors(): void
    {
        $client = new S3VectorsClient([], new NullProvider(), new MockHttpClient());

        $input = new QueryVectorsInput([
            'topK' => 1337,
            'queryVector' => new VectorData([
            ]),
        ]);
        $result = $client->queryVectors($input);

        self::assertInstanceOf(QueryVectorsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
