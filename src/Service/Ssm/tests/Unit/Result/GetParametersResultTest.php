<?php

namespace AsyncAws\Ssm\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Result\GetParametersResult;
use Symfony\Component\HttpClient\MockHttpClient;

class GetParametersResultTest extends TestCase
{
    public function testGetParametersResult(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/ssm/latest/APIReference/API_GetParameters.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new GetParametersResult(new Response($client->request('POST', 'http://localhost'), $client));

        // self::assertTODO(expected, $result->getParameters());
        // self::assertTODO(expected, $result->getInvalidParameters());
    }
}
