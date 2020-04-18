<?php

namespace AsyncAws\Ssm\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Input\GetParametersRequest;

class GetParametersRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new GetParametersRequest([
            'Names' => ['change me'],
            'WithDecryption' => false,
        ]);

        // see https://docs.aws.amazon.com/ssm/latest/APIReference/API_GetParameters.html
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
