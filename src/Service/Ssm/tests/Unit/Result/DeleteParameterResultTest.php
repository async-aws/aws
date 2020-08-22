<?php

namespace AsyncAws\Ssm\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Result\DeleteParameterResult;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteParameterResultTest extends TestCase
{
    public function testDeleteParameterResult(): void
    {
        self::markTestSkipped('Nothing to test');

        // see https://docs.aws.amazon.com/systems-manager/latest/APIReference/API_DeleteParameter.html
        $response = new SimpleMockedResponse('{}');

        $client = new MockHttpClient($response);
        $result = new DeleteParameterResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));
    }
}
