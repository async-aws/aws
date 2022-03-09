<?php

namespace AsyncAws\CodeBuild\Tests\Unit\Input;

use AsyncAws\CodeBuild\Input\StopBuildInput;
use AsyncAws\Core\Test\TestCase;

class StopBuildInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new StopBuildInput([
            'id' => 'build-1',
        ]);

        // see https://docs.aws.amazon.com/codebuild/latest/APIReference/API_StopBuild.html
        $expected = '
        POST / HTTP/1.0
        Content-Type: application/x-amz-json-1.1
        x-amz-target: CodeBuild_20161006.StopBuild

        {
            "id": "build-1"
        }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
