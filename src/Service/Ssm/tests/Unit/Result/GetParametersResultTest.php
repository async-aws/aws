<?php

namespace AsyncAws\Ssm\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Result\GetParametersResult;
use AsyncAws\Ssm\ValueObject\Parameter;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetParametersResultTest extends TestCase
{
    public function testGetParametersResult(): void
    {
        // see https://docs.aws.amazon.com/ssm/latest/APIReference/API_GetParameters.html
        $response = new SimpleMockedResponse('{
            "InvalidParameters": [],
            "Parameters": [
                {
                    "Name": "EC2DevServerType",
                    "Type": "String",
                    "Value": "t2.micro",
                    "Version": 2
                },
                {
                    "Name": "EC2ProdServerType",
                    "Type": "String",
                    "Value": "m4.large",
                    "Version": 1
                },
                {
                    "Name": "EC2TestServerType",
                    "Type": "String",
                    "Value": "t2.large",
                    "Version": 3
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new GetParametersResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertCount(3, $result->getParameters());
        self::assertInstanceOf(Parameter::class, $result->getParameters()[0]);
        self::assertSame('EC2DevServerType', $result->getParameters()[0]->getName());
        self::assertEmpty($result->getInvalidParameters());
    }
}
