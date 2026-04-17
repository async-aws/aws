<?php

namespace AsyncAws\ImageBuilder\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\ImageBuilder\Input\StartImagePipelineExecutionRequest;

class StartImagePipelineExecutionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new StartImagePipelineExecutionRequest([
            'imagePipelineArn' => 'arn:aws:imagebuilder:us-east-1:123456789012:image-pipeline/example',
            'clientToken' => 'test-idempotency-token',
        ]);

        // StartImagePipelineExecution is PUT /StartImagePipelineExecution with a JSON body.
        // see https://docs.aws.amazon.com/imagebuilder/latest/APIReference/API_StartImagePipelineExecution.html
        $expected = '
            PUT /StartImagePipelineExecution HTTP/1.0
            Accept: application/json
            Content-Type: application/json

            {
                "imagePipelineArn": "arn:aws:imagebuilder:us-east-1:123456789012:image-pipeline/example",
                "clientToken": "test-idempotency-token"
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
