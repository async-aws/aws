<?php

namespace AsyncAws\Rekognition\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Input\CreateProjectRequest;

class CreateProjectRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateProjectRequest([
            'ProjectName' => 'new-project',
        ]);

        // see https://docs.aws.amazon.com/rekognition/latest/dg/API_CreateProject.html
        $expected = '
                POST / HTTP/1.0
                Content-Type: application/x-amz-json-1.1
                X-Amz-Target: RekognitionService.CreateProject

                {
                    "ProjectName": "new-project"
                }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
