<?php

namespace AsyncAws\Ssm\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Input\DeleteParameterRequest;

class DeleteParameterRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new DeleteParameterRequest([
            'Name' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/ssm/latest/APIReference/API_DeleteParameter.html
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
