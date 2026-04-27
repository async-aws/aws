<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Input\CancelMessageMoveTaskRequest;

class CancelMessageMoveTaskRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CancelMessageMoveTaskRequest([
            'TaskHandle' => 'eyJ0YXNrSWQiOiJkYzE2OWUwNC0wZTU1LTQ0ZDItYWE5MC1jMDgwY2ExZjM2ZjciLCJzb3VyY2VBcm4iOiJhcm46YXdzOnNxczp1cy1lYXN0LTE6MTc3NzE1MjU3NDM2Ok15RGVhZExldHRlclF1ZXVlIn0=',
        ]);

        // see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_CancelMessageMoveTask.html
        $expected = '
            POST / HTTP/1.1
            Content-Type: application/x-amz-json-1.0
            x-amz-target: AmazonSQS.CancelMessageMoveTask
            accept: application/json

            {
                "TaskHandle": "eyJ0YXNrSWQiOiJkYzE2OWUwNC0wZTU1LTQ0ZDItYWE5MC1jMDgwY2ExZjM2ZjciLCJzb3VyY2VBcm4iOiJhcm46YXdzOnNxczp1cy1lYXN0LTE6MTc3NzE1MjU3NDM2Ok15RGVhZExldHRlclF1ZXVlIn0="
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
