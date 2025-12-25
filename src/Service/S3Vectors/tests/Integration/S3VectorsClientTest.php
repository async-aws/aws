<?php

namespace AsyncAws\S3Vectors\Tests\Integration;

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
use AsyncAws\S3Vectors\S3VectorsClient;
use AsyncAws\S3Vectors\ValueObject\EncryptionConfiguration;
use AsyncAws\S3Vectors\ValueObject\MetadataConfiguration;

class S3VectorsClientTest extends TestCase
{
    public function testCreateIndex(): void
    {
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
        $client = $this->getClient();

        $input = new DeleteVectorBucketInput([
            'vectorBucketName' => 'change me',
            'vectorBucketArn' => 'change me',
        ]);
        $result = $client->deleteVectorBucket($input);

        $result->resolve();
    }

    public function testDeleteVectorBucketPolicy(): void
    {
        $client = $this->getClient();

        $input = new DeleteVectorBucketPolicyInput([
            'vectorBucketName' => 'change me',
            'vectorBucketArn' => 'change me',
        ]);
        $result = $client->deleteVectorBucketPolicy($input);

        $result->resolve();
    }

    public function testDeleteVectors(): void
    {
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
        $client = $this->getClient();

        $input = new GetVectorBucketInput([
            'vectorBucketName' => 'change me',
            'vectorBucketArn' => 'change me',
        ]);
        $result = $client->getVectorBucket($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getVectorBucket());
    }

    public function testGetVectorBucketPolicy(): void
    {
        $client = $this->getClient();

        $input = new GetVectorBucketPolicyInput([
            'vectorBucketName' => 'change me',
            'vectorBucketArn' => 'change me',
        ]);
        $result = $client->getVectorBucketPolicy($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getPolicy());
    }

    public function testListIndexes(): void
    {
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

    public function testListTagsForResource(): void
    {
        $client = $this->getClient();

        $input = new ListTagsForResourceInput([
            'resourceArn' => 'change me',
        ]);
        $result = $client->listTagsForResource($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getTags());
    }

    public function testListVectorBuckets(): void
    {
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

    public function testPutVectorBucketPolicy(): void
    {
        $client = $this->getClient();

        $input = new PutVectorBucketPolicyInput([
            'vectorBucketName' => 'change me',
            'vectorBucketArn' => 'change me',
            'policy' => 'change me',
        ]);
        $result = $client->putVectorBucketPolicy($input);

        $result->resolve();
    }

    public function testTagResource(): void
    {
        $client = $this->getClient();

        $input = new TagResourceInput([
            'resourceArn' => 'change me',
            'tags' => ['change me' => 'change me'],
        ]);
        $result = $client->tagResource($input);

        $result->resolve();
    }

    private function getClient(): S3VectorsClient
    {
        self::fail('Not implemented');

        return new S3VectorsClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
