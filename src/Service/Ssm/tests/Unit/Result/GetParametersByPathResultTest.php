<?php

namespace AsyncAws\Ssm\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Result\GetParametersByPathResult;
use Symfony\Component\HttpClient\MockHttpClient;

class GetParametersByPathResultTest extends TestCase
{
    public function testGetParametersByPathResult(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/ssm/latest/APIReference/API_GetParametersByPath.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new GetParametersByPathResult(new Response($client->request('POST', 'http://localhost'), $client));

        // self::assertTODO(expected, $result->getParameters());
        self::assertSame('changeIt', $result->getNextToken());
    }
}
