<?php

namespace AsyncAws\Ssm\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Input\GetParametersRequest;

class GetParametersRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetParametersRequest([
            'Names' => ['EC2DevServerType', 'EC2TestServerType', 'EC2ProdServerType'],
        ]);

        // see https://docs.aws.amazon.com/systems-manager/latest/APIReference/API_GetParameters.html
        $expected = '
        POST / HTTP/1.0
        Content-Type: application/x-amz-json-1.1
            X-Amz-Target: AmazonSSM.GetParameters

        {
            "Names": [
                "EC2DevServerType",
                "EC2TestServerType",
                "EC2ProdServerType"
            ]
        }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
