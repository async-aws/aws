<?php

namespace AsyncAws\Ssm\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Input\GetParameterRequest;

class GetParameterRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetParameterRequest([
            'Name' => 'MyGitHubPassword',
        ]);

        // see https://docs.aws.amazon.com/systems-manager/latest/APIReference/API_GetParameter.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-Amz-Target: AmazonSSM.GetParameter
            Accept: application/json

            {
                "Name": "MyGitHubPassword"
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
