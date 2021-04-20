<?php

namespace AsyncAws\Rekognition\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Input\CreateCollectionRequest;
use AsyncAws\Rekognition\Input\CreateProjectRequest;
use AsyncAws\Rekognition\Input\DeleteCollectionRequest;
use AsyncAws\Rekognition\Input\DeleteProjectRequest;
use AsyncAws\Rekognition\Input\DetectFacesRequest;
use AsyncAws\Rekognition\Input\GetCelebrityInfoRequest;
use AsyncAws\Rekognition\Input\IndexFacesRequest;
use AsyncAws\Rekognition\Input\ListCollectionsRequest;
use AsyncAws\Rekognition\Input\RecognizeCelebritiesRequest;
use AsyncAws\Rekognition\Input\SearchFacesByImageRequest;
use AsyncAws\Rekognition\RekognitionClient;
use AsyncAws\Rekognition\Result\CreateCollectionResponse;
use AsyncAws\Rekognition\Result\CreateProjectResponse;
use AsyncAws\Rekognition\Result\DeleteCollectionResponse;
use AsyncAws\Rekognition\Result\DeleteProjectResponse;
use AsyncAws\Rekognition\Result\DetectFacesResponse;
use AsyncAws\Rekognition\Result\GetCelebrityInfoResponse;
use AsyncAws\Rekognition\Result\IndexFacesResponse;
use AsyncAws\Rekognition\Result\ListCollectionsResponse;
use AsyncAws\Rekognition\Result\RecognizeCelebritiesResponse;
use AsyncAws\Rekognition\Result\SearchFacesByImageResponse;
use AsyncAws\Rekognition\ValueObject\Image;
use Symfony\Component\HttpClient\MockHttpClient;

class RekognitionClientTest extends TestCase
{
    public function testCreateCollection(): void
    {
        $client = new RekognitionClient([], new NullProvider(), new MockHttpClient());

        $input = new CreateCollectionRequest([
            'CollectionId' => 'change me',
        ]);
        $result = $client->CreateCollection($input);

        self::assertInstanceOf(CreateCollectionResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testCreateProject(): void
    {
        $client = new RekognitionClient([], new NullProvider(), new MockHttpClient());

        $input = new CreateProjectRequest([
            'ProjectName' => 'change me',
        ]);
        $result = $client->CreateProject($input);

        self::assertInstanceOf(CreateProjectResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteCollection(): void
    {
        $client = new RekognitionClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteCollectionRequest([
            'CollectionId' => 'change me',
        ]);
        $result = $client->DeleteCollection($input);

        self::assertInstanceOf(DeleteCollectionResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteProject(): void
    {
        $client = new RekognitionClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteProjectRequest([
            'ProjectArn' => 'change me',
        ]);
        $result = $client->DeleteProject($input);

        self::assertInstanceOf(DeleteProjectResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDetectFaces(): void
    {
        $client = new RekognitionClient([], new NullProvider(), new MockHttpClient());

        $input = new DetectFacesRequest([
            'Image' => new Image([

            ]),

        ]);
        $result = $client->DetectFaces($input);

        self::assertInstanceOf(DetectFacesResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetCelebrityInfo(): void
    {
        $client = new RekognitionClient([], new NullProvider(), new MockHttpClient());

        $input = new GetCelebrityInfoRequest([
            'Id' => '1XJ5dK1a',
        ]);
        $result = $client->GetCelebrityInfo($input);

        self::assertInstanceOf(GetCelebrityInfoResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testIndexFaces(): void
    {
        $client = new RekognitionClient([], new NullProvider(), new MockHttpClient());

        $input = new IndexFacesRequest([
            'CollectionId' => 'change me',
            'Image' => new Image([

            ]),

        ]);
        $result = $client->IndexFaces($input);

        self::assertInstanceOf(IndexFacesResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListCollections(): void
    {
        $client = new RekognitionClient([], new NullProvider(), new MockHttpClient());

        $input = new ListCollectionsRequest([

        ]);
        $result = $client->ListCollections($input);

        self::assertInstanceOf(ListCollectionsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testRecognizeCelebrities(): void
    {
        $client = new RekognitionClient([], new NullProvider(), new MockHttpClient());

        $input = new RecognizeCelebritiesRequest([
            'Image' => new Image([
                'Bytes' => '/9j/4AAQSkZJRgABAQAAAQABAAD/',
            ]),
        ]);
        $result = $client->RecognizeCelebrities($input);

        self::assertInstanceOf(RecognizeCelebritiesResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testSearchFacesByImage(): void
    {
        $client = new RekognitionClient([], new NullProvider(), new MockHttpClient());

        $input = new SearchFacesByImageRequest([
            'CollectionId' => 'change me',
            'Image' => new Image([

            ]),

        ]);
        $result = $client->SearchFacesByImage($input);

        self::assertInstanceOf(SearchFacesByImageResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
