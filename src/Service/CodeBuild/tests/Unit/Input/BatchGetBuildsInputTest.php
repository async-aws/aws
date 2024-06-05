<?php

namespace AsyncAws\CodeBuild\Tests\Unit\Input;

use AsyncAws\CodeBuild\Input\BatchGetBuildsInput;
use AsyncAws\Core\Test\TestCase;

class BatchGetBuildsInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new BatchGetBuildsInput([
            'ids' => ['build1'],
        ]);

        // see https://docs.aws.amazon.com/codebuild/latest/APIReference/API_BatchGetBuilds.html
        $expected = '
        POST / HTTP/1.0
        Content-Type: application/x-amz-json-1.1
        x-amz-target: CodeBuild_20161006.BatchGetBuilds
        Accept: application/json

        {
            "ids": ["build1"]
        }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
