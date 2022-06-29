<?php

namespace AsyncAws\Iot\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Result\DeleteThingTypeResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteThingTypeResponseTest extends TestCase
{
    public function testDeleteThingTypeResponse(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/iot/latest/APIReference/API_DeleteThingType.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new DeleteThingTypeResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));
    }
}
