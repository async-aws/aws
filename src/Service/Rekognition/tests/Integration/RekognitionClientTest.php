<?php

namespace AsyncAws\Rekognition\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Input\CreateCollectionRequest;
use AsyncAws\Rekognition\Input\CreateProjectRequest;
use AsyncAws\Rekognition\Input\DeleteCollectionRequest;
use AsyncAws\Rekognition\Input\DetectFacesRequest;
use AsyncAws\Rekognition\Input\IndexFacesRequest;
use AsyncAws\Rekognition\Input\ListCollectionsRequest;
use AsyncAws\Rekognition\RekognitionClient;
use AsyncAws\Rekognition\ValueObject\Image;
use AsyncAws\Rekognition\ValueObject\S3Object;

class RekognitionClientTest extends TestCase
{
    private function getClient(): RekognitionClient
    {
        self::fail('Not implemented');

        return new RekognitionClient([
        'endpoint' => 'http://localhost',
        ], new NullProvider());
    }

    public function testCreateCollection(): void
    {
        $client = $this->getClient();

        $input = new CreateCollectionRequest([
        'CollectionId' => 'change me',
        ]);
        $result = $client->CreateCollection($input);

        $result->resolve();

        self::assertSame(1337, $result->getStatusCode());
        self::assertSame("changeIt", $result->getCollectionArn());
        self::assertSame("changeIt", $result->getFaceModelVersion());
    }

    public function testCreateProject(): void
    {
        $client = $this->getClient();

        $input = new CreateProjectRequest([
        'ProjectName' => 'change me',
        ]);
        $result = $client->CreateProject($input);

        $result->resolve();

        self::assertSame("changeIt", $result->getProjectArn());
    }

    public function testDeleteCollection(): void
    {
        $client = $this->getClient();

        $input = new DeleteCollectionRequest([
        'CollectionId' => 'change me',
        ]);
        $result = $client->DeleteCollection($input);

        $result->resolve();

        self::assertSame(1337, $result->getStatusCode());
    }

    public function testDetectFaces(): void
    {
        $client = $this->getClient();

        $input = new DetectFacesRequest([
        'Image' => new Image([
        'Bytes' => 'change me',
        'S3Object' => new S3Object([
        'Bucket' => 'change me',
        'Name' => 'change me',
        'Version' => 'change me',
        ]),
        ]),
        'Attributes' => ['change me'],
        ]);
        $result = $client->DetectFaces($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getFaceDetails());
        self::assertSame("changeIt", $result->getOrientationCorrection());
    }

    public function testIndexFaces(): void
    {
        $client = $this->getClient();

        $input = new IndexFacesRequest([
        'CollectionId' => 'change me',
        'Image' => new Image([
        'Bytes' => 'change me',
        'S3Object' => new S3Object([
        'Bucket' => 'change me',
        'Name' => 'change me',
        'Version' => 'change me',
        ]),
        ]),
        'ExternalImageId' => 'change me',
        'DetectionAttributes' => ['change me'],
        'MaxFaces' => 1337,
        'QualityFilter' => 'change me',
        ]);
        $result = $client->IndexFaces($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getFaceRecords());
        self::assertSame("changeIt", $result->getOrientationCorrection());
        self::assertSame("changeIt", $result->getFaceModelVersion());
        // self::assertTODO(expected, $result->getUnindexedFaces());
    }

    public function testListCollections(): void
    {
        $client = $this->getClient();

                        $input = new ListCollectionsRequest([
                            'NextToken' => 'change me',
        'MaxResults' => 1337,
                        ]);
                        $result = $client->ListCollections($input);

                        $result->resolve();

                        // self::assertTODO(expected, $result->getCollectionIds());
        self::assertSame("changeIt", $result->getNextToken());
        // self::assertTODO(expected, $result->getFaceModelVersions());
    }
}
