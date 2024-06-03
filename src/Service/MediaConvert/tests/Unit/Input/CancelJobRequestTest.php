<?php

namespace AsyncAws\MediaConvert\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\MediaConvert\Input\CancelJobRequest;

class CancelJobRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CancelJobRequest([
            'Id' => 'ZJ1648461',
        ]);

        // see https://docs.aws.amazon.com/mediaconvert/latest/apireference/API_CancelJob.html
        $expected = '
            DELETE /2017-08-29/jobs/ZJ1648461 HTTP/1.0
            Content-type: application/json
            Accept: application/json

                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
