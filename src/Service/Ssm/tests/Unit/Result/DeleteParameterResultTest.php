<?php

namespace AsyncAws\Ssm\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Result\DeleteParameterResult;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteParameterResultTest extends TestCase
{
    public function testDeleteParameterResult(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/ssm/latest/APIReference/API_DeleteParameter.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new DeleteParameterResult(new Response($client->request('POST', 'http://localhost'), $client));
    }
}
