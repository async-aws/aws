<?php

namespace AsyncAws\Lambda\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\ListFunctionsRequest;
use AsyncAws\Lambda\LambdaClient;
use AsyncAws\Lambda\Result\ListFunctionsResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListFunctionsResponseTest extends TestCase
{
    public function testListFunctionsResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "Functions": [
                {
                    "CodeSha256": "dBG9m8SGdmlEjw\\/JYXlhhvCrAv5TxvXsbL\\/RMr0fT\\/I=",
                    "CodeSize": 294,
                    "Description": "",
                    "FunctionArn": "arn:aws:lambda:us-west-2:123456789012:function:helloworld",
                    "FunctionName": "helloworld",
                    "Handler": "helloworld.handler",
                    "LastModified": "2019-09-23T18:32:33.857+0000",
                    "MemorySize": 128,
                    "RevisionId": "1718e831-badf-4253-9518-d0644210af7b",
                    "Role": "arn:aws:iam::123456789012:role\\/service-role\\/MyTestFunction-role-zgur6bf4",
                    "Runtime": "nodejs10.x",
                    "Timeout": 3,
                    "TracingConfig": {
                        "Mode": "PassThrough"
                    },
                    "Version": "$LATEST"
                },
                {
                    "CodeSha256": "sU0cJ2\\/hOZevwV\\/lTxCuQqK3gDZP3i8gUoqUUVRmY6E=",
                    "CodeSize": 266,
                    "Description": "",
                    "FunctionArn": "arn:aws:lambda:us-west-2:123456789012:function:my-function",
                    "FunctionName": "my-function",
                    "Handler": "index.handler",
                    "LastModified": "2019-10-01T16:47:28.490+0000",
                    "MemorySize": 256,
                    "RevisionId": "93017fc9-59cb-41dc-901b-4845ce4bf668",
                    "Role": "arn:aws:iam::123456789012:role\\/service-role\\/helloWorldPython-role-uy3l9qyq",
                    "Runtime": "nodejs10.x",
                    "Timeout": 3,
                    "TracingConfig": {
                        "Mode": "PassThrough"
                    },
                    "Version": "$LATEST",
                    "VpcConfig": {
                        "SecurityGroupIds": [],
                        "SubnetIds": [],
                        "VpcId": ""
                    }
                }
            ],
            "NextMarker": "xxyy"
        }');

        $client = new MockHttpClient($response);
        $result = new ListFunctionsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new LambdaClient(), new ListFunctionsRequest([]));

        self::assertSame('xxyy', $result->getNextMarker());

        foreach ($result->getFunctions(true) as $function) {
            self::assertSame('helloworld', $function->getFunctionName());
            self::assertSame('arn:aws:lambda:us-west-2:123456789012:function:helloworld', $function->getFunctionArn());
            self::assertSame('$LATEST', $function->getVersion());
            self::assertSame(294, $function->getCodeSize());

            break;
        }
    }
}
