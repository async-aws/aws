<?php

namespace AsyncAws\Ssm\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Input\GetParameterRequest;

class GetParameterRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new GetParameterRequest([
            'Name' => 'change me',
            'WithDecryption' => false,
        ]);

        // see https://docs.aws.amazon.com/ssm/latest/APIReference/API_GetParameter.html
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
