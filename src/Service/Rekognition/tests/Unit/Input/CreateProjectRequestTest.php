<?php

namespace AsyncAws\Rekognition\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Input\CreateProjectRequest;

class CreateProjectRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

                $input = new CreateProjectRequest([
                            'ProjectName' => 'change me',
                        ]);

                // see https://docs.aws.amazon.com/rekognition/latest/APIReference/API_CreateProject.html
                $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1

            {
            "change": "it"
        }
                ';

                self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
