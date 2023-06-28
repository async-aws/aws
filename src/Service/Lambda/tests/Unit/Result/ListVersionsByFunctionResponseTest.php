<?php

namespace AsyncAws\Lambda\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\ListVersionsByFunctionRequest;
use AsyncAws\Lambda\LambdaClient;
use AsyncAws\Lambda\Result\ListVersionsByFunctionResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListVersionsByFunctionResponseTest extends TestCase
{
    public function testListVersionsByFunctionResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "Versions": [
                {
                    "CodeSha256": "YFgDgEKG3ugvF1+pX64gV6tu9qNuIYNUdgJm8nCxsm4=",
                    "CodeSize": 5797206,
                    "Description": "Process image objects from Amazon S3.",
                    "Environment": {
                        "Variables": {
                            "BUCKET": "my-bucket-1xpuxmplzrlbh",
                            "PREFIX": "inbound"
                        }
                    },
                    "FunctionArn": "arn:aws:lambda:us-west-2:123456789012:function:my-function",
                    "FunctionName": "my-function",
                    "Handler": "index.handler",
                    "KMSKeyArn": "arn:aws:kms:us-west-2:123456789012:key\\/b0844d6c-xmpl-4463-97a4-d49f50839966",
                    "LastModified": "2020-04-10T19:06:32.563+0000",
                    "MemorySize": 256,
                    "RevisionId": "850ca006-2d98-4ff4-86db-8766e9d32fe9",
                    "Role": "arn:aws:iam::123456789012:role\\/lambda-role",
                    "Runtime": "nodejs12.x",
                    "Timeout": 15,
                    "TracingConfig": {
                        "Mode": "Active"
                    },
                    "Version": "$LATEST"
                },
                {
                    "CodeSha256": "YFgDgEKG3ugvF1+pX64gV6tu9qNuIYNUdgJm8nCxsm4=",
                    "CodeSize": 5797206,
                    "Description": "Process image objects from Amazon S3.",
                    "Environment": {
                        "Variables": {
                            "BUCKET": "my-bucket-1xpuxmplzrlbh",
                            "PREFIX": "inbound"
                        }
                    },
                    "FunctionArn": "arn:aws:lambda:us-west-2:123456789012:function:my-function",
                    "FunctionName": "my-function",
                    "Handler": "index.handler",
                    "KMSKeyArn": "arn:aws:kms:us-west-2:123456789012:key\\/b0844d6c-xmpl-4463-97a4-d49f50839966",
                    "LastModified": "2020-04-10T19:06:32.563+0000",
                    "MemorySize": 256,
                    "RevisionId": "b75dcd81-xmpl-48a8-a75a-93ba8b5b9727",
                    "Role": "arn:aws:iam::123456789012:role\\/lambda-role",
                    "Runtime": "nodejs12.x",
                    "Timeout": 5,
                    "TracingConfig": {
                        "Mode": "Active"
                    },
                    "Version": "1"
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new ListVersionsByFunctionResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new LambdaClient(), new ListVersionsByFunctionRequest([]));

        foreach ($result->getVersions(true) as $version) {
            self::assertSame('my-function', $version->getFunctionName());
            self::assertSame('arn:aws:lambda:us-west-2:123456789012:function:my-function', $version->getFunctionArn());
            self::assertSame('$LATEST', $version->getVersion());
            self::assertSame(5797206, $version->getCodeSize());

            break;
        }
    }
}
