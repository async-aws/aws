<?php

namespace AsyncAws\Ssm\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Input\DeleteParameterRequest;

class DeleteParameterRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteParameterRequest([
            'Name' => 'EC2DevServerType',
        ]);

        // see https://docs.aws.amazon.com/systems-manager/latest/APIReference/API_DeleteParameter.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-Amz-Target: AmazonSSM.DeleteParameter

            {
                "Name": "EC2DevServerType"
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
