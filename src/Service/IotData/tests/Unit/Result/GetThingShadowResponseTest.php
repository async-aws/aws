<?php

namespace AsyncAws\IotData\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\IotData\Result\GetThingShadowResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetThingShadowResponseTest extends TestCase
{
    public function testGetThingShadowResponse(): void
    {
        // see https://docs.aws.amazon.com/iot/latest/apireference/API_Operations_AWS_IoT_Data_Plane.html/API_GetThingShadow.html
        $response = new SimpleMockedResponse('{
            "state": {
                "reported": {
                    "temperature": 20
                }
            }
        }');

        $client = new MockHttpClient($response);
        $result = new GetThingShadowResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertJsonStringEqualsJsonString('{"state": {"reported": {"temperature": 20}}}', $result->getPayload());
    }
}
