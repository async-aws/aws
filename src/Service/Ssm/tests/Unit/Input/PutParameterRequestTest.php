<?php

namespace AsyncAws\Ssm\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Enum\ParameterType;
use AsyncAws\Ssm\Input\PutParameterRequest;

class PutParameterRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PutParameterRequest([
            'Name' => 'EC2TestServerType',
            'Description' => 'Instance type for Test servers',
            'Value' => 't2.large',
            'Type' => ParameterType::STRING,
            'Overwrite' => true,
        ]);

        // see https://docs.aws.amazon.com/ssm/latest/APIReference/API_PutParameter.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-Amz-Target: AmazonSSM.PutParameter

            {
                "Overwrite": true,
                "Type": "String",
                "Name": "EC2TestServerType",
                "Value": "t2.large",
                "Description": "Instance type for Test servers"
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
