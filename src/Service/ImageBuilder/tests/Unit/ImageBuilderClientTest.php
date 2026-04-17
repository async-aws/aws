<?php

namespace AsyncAws\ImageBuilder\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\ImageBuilder\ImageBuilderClient;
use AsyncAws\ImageBuilder\Input\DeleteImageRequest;
use AsyncAws\ImageBuilder\Input\GetImageRequest;
use AsyncAws\ImageBuilder\Input\ListImageBuildVersionsRequest;
use AsyncAws\ImageBuilder\Input\ListImagesRequest;
use AsyncAws\ImageBuilder\Input\StartImagePipelineExecutionRequest;
use AsyncAws\ImageBuilder\Result\DeleteImageResponse;
use AsyncAws\ImageBuilder\Result\GetImageResponse;
use AsyncAws\ImageBuilder\Result\ListImageBuildVersionsResponse;
use AsyncAws\ImageBuilder\Result\ListImagesResponse;
use AsyncAws\ImageBuilder\Result\StartImagePipelineExecutionResponse;
use Symfony\Component\HttpClient\MockHttpClient;

class ImageBuilderClientTest extends TestCase
{
    public function testDeleteImage(): void
    {
        $client = new ImageBuilderClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteImageRequest([
            'imageBuildVersionArn' => 'arn:aws:imagebuilder:us-east-1:123456789012:image/example/1.0.0/1',
        ]);
        $result = $client->deleteImage($input);

        self::assertInstanceOf(DeleteImageResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetImage(): void
    {
        $client = new ImageBuilderClient([], new NullProvider(), new MockHttpClient());

        $input = new GetImageRequest([
            'imageBuildVersionArn' => 'arn:aws:imagebuilder:us-east-1:123456789012:image/example/1.0.0/1',
        ]);
        $result = $client->getImage($input);

        self::assertInstanceOf(GetImageResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListImageBuildVersions(): void
    {
        $client = new ImageBuilderClient([], new NullProvider(), new MockHttpClient());

        $input = new ListImageBuildVersionsRequest([
        ]);
        $result = $client->listImageBuildVersions($input);

        self::assertInstanceOf(ListImageBuildVersionsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListImages(): void
    {
        $client = new ImageBuilderClient([], new NullProvider(), new MockHttpClient());

        $input = new ListImagesRequest([
        ]);
        $result = $client->listImages($input);

        self::assertInstanceOf(ListImagesResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testStartImagePipelineExecution(): void
    {
        $client = new ImageBuilderClient([], new NullProvider(), new MockHttpClient());

        $input = new StartImagePipelineExecutionRequest([
            'imagePipelineArn' => 'arn:aws:imagebuilder:us-east-1:123456789012:image-pipeline/example',
            'clientToken' => '7f9c9a1e-1234-4abc-8def-0123456789ab',
        ]);
        $result = $client->startImagePipelineExecution($input);

        self::assertInstanceOf(StartImagePipelineExecutionResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
