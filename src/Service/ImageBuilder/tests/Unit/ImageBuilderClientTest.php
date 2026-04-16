<?php

namespace AsyncAws\ImageBuilder\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\ImageBuilder\ImageBuilderClient;
use AsyncAws\ImageBuilder\Input\GetImageRequest;
use AsyncAws\ImageBuilder\Input\StartImagePipelineExecutionRequest;
use AsyncAws\ImageBuilder\Result\GetImageResponse;
use AsyncAws\ImageBuilder\Result\StartImagePipelineExecutionResponse;
use Symfony\Component\HttpClient\MockHttpClient;

class ImageBuilderClientTest extends TestCase
{
    public function testGetImage(): void
    {
        $client = new ImageBuilderClient([], new NullProvider(), new MockHttpClient());

        $input = new GetImageRequest([
            'imageBuildVersionArn' => 'change me',
        ]);
        $result = $client->getImage($input);

        self::assertInstanceOf(GetImageResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testStartImagePipelineExecution(): void
    {
        $client = new ImageBuilderClient([], new NullProvider(), new MockHttpClient());

        $input = new StartImagePipelineExecutionRequest([
            'imagePipelineArn' => 'change me',
            'clientToken' => 'change me',
        ]);
        $result = $client->startImagePipelineExecution($input);

        self::assertInstanceOf(StartImagePipelineExecutionResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
