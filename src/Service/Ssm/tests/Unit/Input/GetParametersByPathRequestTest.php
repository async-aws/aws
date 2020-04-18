<?php

namespace AsyncAws\Ssm\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Input\GetParametersByPathRequest;
use AsyncAws\Ssm\ValueObject\ParameterStringFilter;

class GetParametersByPathRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new GetParametersByPathRequest([
            'Path' => 'change me',
            'Recursive' => false,
            'ParameterFilters' => [new ParameterStringFilter([
                'Key' => 'change me',
                'Option' => 'change me',
                'Values' => ['change me'],
            ])],
            'WithDecryption' => false,
            'MaxResults' => 1337,
            'NextToken' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/ssm/latest/APIReference/API_GetParametersByPath.html
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
