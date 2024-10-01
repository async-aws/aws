<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Input\AddPermissionRequest;

class AddPermissionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new AddPermissionRequest([
            'QueueUrl' => 'change me',
            'Label' => 'change me',
            'AWSAccountIds' => ['change me'],
            'Actions' => ['change me'],
        ]);

        // see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_AddPermission.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
