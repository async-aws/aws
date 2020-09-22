<?php

namespace AsyncAws\Rekognition\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Input\DeleteProjectRequest;

class DeleteProjectRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteProjectRequest([
            'ProjectArn' => 'arn:aws:rekognition:*:*:project/MyProject/version/MyVersion/*',
        ]);

        // see https://docs.aws.amazon.com/rekognition/latest/dg/API_DeleteProject.html
        $expected = '
                POST / HTTP/1.0
                Content-Type: application/x-amz-json-1.1
                X-Amz-Target: RekognitionService.DeleteProject

                {
                    "ProjectArn": "arn:aws:rekognition:*:*:project/MyProject/version/MyVersion/*"
                }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
