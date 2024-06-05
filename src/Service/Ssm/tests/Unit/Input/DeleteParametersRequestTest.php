<?php

namespace AsyncAws\Ssm\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Input\DeleteParametersRequest;

class DeleteParametersRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteParametersRequest([
            'Names' => ['DB_HOST'],
        ]);

        // see https://docs.aws.amazon.com/systems-manager/latest/APIReference/API_DeleteParameters.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-Amz-Target: AmazonSSM.DeleteParameters
            Accept: application/json

            {
                "Names": [
                    "DB_HOST"
                ]
            }
            ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
