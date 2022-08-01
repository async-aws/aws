<?php

namespace AsyncAws\IotData\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\IotData\Result\UpdateThingShadowResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class UpdateThingShadowResponseTest extends TestCase
{
    public function testUpdateThingShadowResponse(): void
    {
        // see https://docs.aws.amazon.com/iot/latest/apireference/API_Operations_AWS_IoT_Data_Plane.html/API_UpdateThingShadow.html
        $response = new SimpleMockedResponse('{
            "state": {
                "reported": {
                    "temperature": 21
                }
            }
        }');

        $client = new MockHttpClient($response);
        $result = new UpdateThingShadowResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertJsonStringEqualsJsonString('{"state": {"reported": {"temperature": 21}}}', $result->getPayload());
    }
}
