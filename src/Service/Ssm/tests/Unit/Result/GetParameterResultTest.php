<?php

namespace AsyncAws\Ssm\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Result\GetParameterResult;
use Symfony\Component\HttpClient\MockHttpClient;

class GetParameterResultTest extends TestCase
{
    public function testGetParameterResult(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/ssm/latest/APIReference/API_GetParameter.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new GetParameterResult(new Response($client->request('POST', 'http://localhost'), $client));

        // self::assertTODO(expected, $result->getParameter());
    }
}
