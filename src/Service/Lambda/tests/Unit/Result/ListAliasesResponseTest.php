<?php

namespace AsyncAws\Lambda\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\ListAliasesRequest;
use AsyncAws\Lambda\LambdaClient;
use AsyncAws\Lambda\Result\ListAliasesResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListAliasesResponseTest extends TestCase
{
    public function testListAliasesResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "Aliases": [
                {
                    "AliasArn": "arn:aws:lambda:us-west-2:123456789012:function:my-function:BETA",
                    "Description": "Production environment BLUE.",
                    "FunctionVersion": "2",
                    "Name": "BLUE",
                    "RevisionId": "a410117f-xmpl-494e-8035-7e204bb7933b",
                    "RoutingConfig": {
                        "AdditionalVersionWeights": {
                            "1": 0.7
                        }
                    }
                },
                {
                    "AliasArn": "arn:aws:lambda:us-west-2:123456789012:function:my-function:LIVE",
                    "Description": "Production environment GREEN.",
                    "FunctionVersion": "1",
                    "Name": "GREEN",
                    "RevisionId": "21d40116-xmpl-40ba-9360-3ea284da1bb5"
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new ListAliasesResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new LambdaClient(), new ListAliasesRequest([]));

        self::assertNull($result->getNextMarker());

        $aliases = iterator_to_array($result->getAliases(true), false);
        self::assertCount(2, $aliases);

        self::assertSame('BLUE', $aliases[0]->getName());
        self::assertSame('2', $aliases[0]->getFunctionVersion());
        self::assertSame('arn:aws:lambda:us-west-2:123456789012:function:my-function:BETA', $aliases[0]->getAliasArn());
        self::assertSame('Production environment BLUE.', $aliases[0]->getDescription());
        self::assertSame('a410117f-xmpl-494e-8035-7e204bb7933b', $aliases[0]->getRevisionId());
        self::assertSame(['1' => 0.7], $aliases[0]->getRoutingConfig()->getAdditionalVersionWeights());

        self::assertSame('GREEN', $aliases[1]->getName());
        self::assertSame('1', $aliases[1]->getFunctionVersion());
        self::assertNull($aliases[1]->getRoutingConfig());
    }
}
