<?php

namespace AsyncAws\Ssm\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Result\PutParameterResult;
use Symfony\Component\HttpClient\MockHttpClient;

class PutParameterResultTest extends TestCase
{
    public function testPutParameterResult(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/ssm/latest/APIReference/API_PutParameter.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new PutParameterResult(new Response($client->request('POST', 'http://localhost'), $client));

        self::assertSame(1337, $result->getVersion());
        self::assertSame('changeIt', $result->getTier());
    }
}
