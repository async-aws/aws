<?php

namespace AsyncAws\ImageBuilder\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\ImageBuilder\ImageBuilderClient;
use AsyncAws\ImageBuilder\Input\GetImageRequest;
use AsyncAws\ImageBuilder\Input\StartImagePipelineExecutionRequest;

class ImageBuilderClientTest extends TestCase
{
    public function testGetImage(): void
    {
        $client = $this->getClient();

        $input = new GetImageRequest([
            'imageBuildVersionArn' => 'change me',
        ]);
        $result = $client->getImage($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getRequestId());
        // self::assertTODO(expected, $result->getImage());
        // self::assertTODO(expected, $result->getLatestVersionReferences());
    }

    public function testStartImagePipelineExecution(): void
    {
        $client = $this->getClient();

        $input = new StartImagePipelineExecutionRequest([
            'imagePipelineArn' => 'change me',
            'clientToken' => 'change me',
            'tags' => ['change me' => 'change me'],
        ]);
        $result = $client->startImagePipelineExecution($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getRequestId());
        self::assertSame('changeIt', $result->getClientToken());
        self::assertSame('changeIt', $result->getImageBuildVersionArn());
    }

    private function getClient(): ImageBuilderClient
    {
        self::markTestSkipped('There is no docker image available for ImageBuilder.');

        return new ImageBuilderClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
