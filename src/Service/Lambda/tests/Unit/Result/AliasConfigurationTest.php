<?php

namespace AsyncAws\Lambda\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Result\AliasConfiguration;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class AliasConfigurationTest extends TestCase
{
    public function testAliasConfiguration(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "AliasArn": "arn:aws:lambda:us-west-2:123456789012:function:my-function:BLUE",
            "Description": "Production environment BLUE.",
            "FunctionVersion": "3",
            "Name": "BLUE",
            "RevisionId": "594f41fb-xmpl-4c20-95c7-6ca5f2a92c93"
        }');

        $client = new MockHttpClient($response);
        $result = new AliasConfiguration(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('arn:aws:lambda:us-west-2:123456789012:function:my-function:BLUE', $result->getAliasArn());
        self::assertSame('BLUE', $result->getName());
        self::assertSame('3', $result->getFunctionVersion());
        self::assertSame('Production environment BLUE.', $result->getDescription());
        self::assertSame('594f41fb-xmpl-4c20-95c7-6ca5f2a92c93', $result->getRevisionId());
        self::assertNull($result->getRoutingConfig());
    }
}
