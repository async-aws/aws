<?php

namespace AsyncAws\MediaConvert\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\MediaConvert\Input\GetJobRequest;

class GetJobRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetJobRequest([
            'Id' => 'ZJ1648461',
        ]);

        // see https://docs.aws.amazon.com/mediaconvert/latest/apireference/API_GetJob.html
        $expected = '
            GET /2017-08-29/jobs/ZJ1648461 HTTP/1.0
            Content-Type: application/json

                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
