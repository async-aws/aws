<?php

namespace AsyncAws\Iot\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Result\DeleteThingResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteThingResponseTest extends TestCase
{
    public function testDeleteThingResponse(): void
    {
        // see https://docs.aws.amazon.com/iot/latest/apireference/API_DeleteThing.html
        $response = new SimpleMockedResponse('{
        }');

        $client = new MockHttpClient($response);
        $result = new DeleteThingResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));
        self::assertTrue($result->resolve());
    }
}
