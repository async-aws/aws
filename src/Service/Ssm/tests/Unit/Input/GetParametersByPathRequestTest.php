<?php

namespace AsyncAws\Ssm\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Input\GetParametersByPathRequest;

class GetParametersByPathRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetParametersByPathRequest([
            'Path' => '/Branch312/Dev/',
            'Recursive' => true,
        ]);

        // see https://docs.aws.amazon.com/ssm/latest/APIReference/API_GetParametersByPath.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-Amz-Target: AmazonSSM.GetParametersByPath

            {
                "Path": "/Branch312/Dev/",
                "Recursive": true
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
