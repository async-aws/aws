<?php

namespace AsyncAws\S3Vectors\Tests\Integration;

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
use AsyncAws\S3Vectors\S3VectorsClient;
use AsyncAws\S3Vectors\ValueObject\EncryptionConfiguration;
use AsyncAws\S3Vectors\ValueObject\MetadataConfiguration;
use AsyncAws\S3Vectors\ValueObject\PutInputVector;
use AsyncAws\S3Vectors\ValueObject\VectorData;
use AsyncAws\S3Vectors\ValueObject\VectorDataMemberFloat32;

class S3VectorsClientTest extends TestCase
{
    public function testCreateIndex(): void
    {
        self::markTestSkipped('localstack doesn\'t support s3 vectors api yet (see https://github.com/localstack/localstack/issues/13498)');

        $client = $this->getClient();

        $input = new CreateIndexInput([
            'vectorBucketName' => 'change me',
            'vectorBucketArn' => 'change me',
            'indexName' => 'change me',
            'dataType' => 'change me',
            'dimension' => 1337,
            'distanceMetric' => 'change me',
            'metadataConfiguration' => new MetadataConfiguration([
                'nonFilterableMetadataKeys' => ['change me'],
            ]),
            'encryptionConfiguration' => new EncryptionConfiguration([
                'sseType' => 'change me',
                'kmsKeyArn' => 'change me',
            ]),
            'tags' => ['change me' => 'change me'],
        ]);
        $result = $client->createIndex($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getIndexArn());
    }

    public function testCreateVectorBucket(): void
    {
        self::markTestSkipped('localstack doesn\'t support s3 vectors api yet (see https://github.com/localstack/localstack/issues/13498)');

        $client = $this->getClient();

        $input = new CreateVectorBucketInput([
            'vectorBucketName' => 'change me',
            'encryptionConfiguration' => new EncryptionConfiguration([
                'sseType' => 'change me',
                'kmsKeyArn' => 'change me',
            ]),
            'tags' => ['change me' => 'change me'],
        ]);
        $result = $client->createVectorBucket($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getVectorBucketArn());
    }

    public function testDeleteIndex(): void
    {
        self::markTestSkipped('localstack doesn\'t support s3 vectors api yet (see https://github.com/localstack/localstack/issues/13498)');

        $client = $this->getClient();

        $input = new DeleteIndexInput([
            'vectorBucketName' => 'change me',
            'indexName' => 'change me',
            'indexArn' => 'change me',
        ]);
        $result = $client->deleteIndex($input);

        $result->resolve();
    }

    public function testDeleteVectorBucket(): void
    {
        self::markTestSkipped('localstack doesn\'t support s3 vectors api yet (see https://github.com/localstack/localstack/issues/13498)');

        $client = $this->getClient();

        $input = new DeleteVectorBucketInput([
            'vectorBucketName' => 'change me',
            'vectorBucketArn' => 'change me',
        ]);
        $result = $client->deleteVectorBucket($input);

        $result->resolve();
    }

    public function testDeleteVectors(): void
    {
        self::markTestSkipped('localstack doesn\'t support s3 vectors api yet (see https://github.com/localstack/localstack/issues/13498)');

        $client = $this->getClient();

        $input = new DeleteVectorsInput([
            'vectorBucketName' => 'change me',
            'indexName' => 'change me',
            'indexArn' => 'change me',
            'keys' => ['change me'],
        ]);
        $result = $client->deleteVectors($input);

        $result->resolve();
    }

    public function testGetIndex(): void
    {
        self::markTestSkipped('localstack doesn\'t support s3 vectors api yet (see https://github.com/localstack/localstack/issues/13498)');

        $client = $this->getClient();

        $input = new GetIndexInput([
            'vectorBucketName' => 'change me',
            'indexName' => 'change me',
            'indexArn' => 'change me',
        ]);
        $result = $client->getIndex($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getIndex());
    }

    public function testGetVectorBucket(): void
    {
        self::markTestSkipped('localstack doesn\'t support s3 vectors api yet (see https://github.com/localstack/localstack/issues/13498)');

        $client = $this->getClient();

        $input = new GetVectorBucketInput([
            'vectorBucketName' => 'change me',
            'vectorBucketArn' => 'change me',
        ]);
        $result = $client->getVectorBucket($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getVectorBucket());
    }

    public function testGetVectors(): void
    {
        self::markTestSkipped('localstack doesn\'t support s3 vectors api yet (see https://github.com/localstack/localstack/issues/13498)');

        $client = $this->getClient();

        $input = new GetVectorsInput([
            'vectorBucketName' => 'change me',
            'indexName' => 'change me',
            'indexArn' => 'change me',
            'keys' => ['change me'],
            'returnData' => false,
            'returnMetadata' => false,
        ]);
        $result = $client->getVectors($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getVectors());
    }

    public function testListIndexes(): void
    {
        self::markTestSkipped('localstack doesn\'t support s3 vectors api yet (see https://github.com/localstack/localstack/issues/13498)');

        $client = $this->getClient();

        $input = new ListIndexesInput([
            'vectorBucketName' => 'change me',
            'vectorBucketArn' => 'change me',
            'maxResults' => 1337,
            'nextToken' => 'change me',
            'prefix' => 'change me',
        ]);
        $result = $client->listIndexes($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getNextToken());
        // self::assertTODO(expected, $result->getIndexes());
    }

    public function testListVectorBuckets(): void
    {
        self::markTestSkipped('localstack doesn\'t support s3 vectors api yet (see https://github.com/localstack/localstack/issues/13498)');

        $client = $this->getClient();

        $input = new ListVectorBucketsInput([
            'maxResults' => 1337,
            'nextToken' => 'change me',
            'prefix' => 'change me',
        ]);
        $result = $client->listVectorBuckets($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getNextToken());
        // self::assertTODO(expected, $result->getVectorBuckets());
    }

    public function testListVectors(): void
    {
        self::markTestSkipped('localstack doesn\'t support s3 vectors api yet (see https://github.com/localstack/localstack/issues/13498)');

        $client = $this->getClient();

        $input = new ListVectorsInput([
            'vectorBucketName' => 'change me',
            'indexName' => 'change me',
            'indexArn' => 'change me',
            'maxResults' => 1337,
            'nextToken' => 'change me',
            'segmentCount' => 1337,
            'segmentIndex' => 1337,
            'returnData' => false,
            'returnMetadata' => false,
        ]);
        $result = $client->listVectors($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getNextToken());
        // self::assertTODO(expected, $result->getVectors());
    }

    public function testPutVectors(): void
    {
        self::markTestSkipped('localstack doesn\'t support s3 vectors api yet (see https://github.com/localstack/localstack/issues/13498)');

        $client = $this->getClient();

        $input = new PutVectorsInput([
            'vectorBucketName' => 'change me',
            'indexName' => 'change me',
            'indexArn' => 'change me',
            'vectors' => [new PutInputVector([
                'key' => 'change me',
                'data' => new VectorData([
                    'float32' => [1337],
                ]),
                'metadata' => 'change me',
            ])],
        ]);
        $result = $client->putVectors($input);

        $result->resolve();
    }

    public function testQueryVectors(): void
    {
        self::markTestSkipped('localstack doesn\'t support s3 vectors api yet (see https://github.com/localstack/localstack/issues/13498)');

        $client = $this->getClient();

        $input = new QueryVectorsInput([
            'vectorBucketName' => 'change me',
            'indexName' => 'change me',
            'indexArn' => 'change me',
            'topK' => 1337,
            'queryVector' => new VectorDataMemberFloat32([
                'float32' => [1337],
            ]),
            'filter' => 'change me',
            'returnMetadata' => false,
            'returnDistance' => false,
        ]);
        $result = $client->queryVectors($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getVectors());
        self::assertSame('changeIt', $result->getDistanceMetric());
    }

    private function getClient(): S3VectorsClient
    {
        self::fail('Not implemented');

        return new S3VectorsClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
