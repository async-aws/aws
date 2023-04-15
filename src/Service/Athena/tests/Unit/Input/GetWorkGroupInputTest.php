<?php

namespace AsyncAws\Athena\Tests\Unit\Input;

use AsyncAws\Athena\Input\GetWorkGroupInput;
use AsyncAws\Core\Test\TestCase;

class GetWorkGroupInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetWorkGroupInput([
            'WorkGroup' => 'iadinternational',
        ]);

        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetWorkGroup.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: AmazonAthena.GetWorkGroup

            {
            "WorkGroup": "iadinternational"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
