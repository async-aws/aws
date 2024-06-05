<?php

namespace AsyncAws\CodeCommit\Tests\Unit\Input;

use AsyncAws\CodeCommit\Input\GetBlobInput;
use AsyncAws\Core\Test\TestCase;

class GetBlobInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetBlobInput([
            'repositoryName' => 'MyFirstRepository',
            'blobId' => 'abc123',
        ]);

        // see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_GetBlob.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: CodeCommit_20150413.GetBlob
            Accept: application/json

            {
            "repositoryName": "MyFirstRepository",
            "blobId": "abc123"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
