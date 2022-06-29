<?php

namespace AsyncAws\Iot\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\ListThingsRequest;
use AsyncAws\Iot\IotClient;
use AsyncAws\Iot\Result\ListThingsResponse;
use AsyncAws\Iot\ValueObject\ThingAttribute;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListThingsResponseTest extends TestCase
{
    public function testListThingsResponse(): void
    {
        // see https://docs.aws.amazon.com/iot/latest/apireference/API_ListThings.html
        $response = new SimpleMockedResponse('{
            "things": [
                {
                   "attributes": {
                      "unit_id" : "1"
                   },
                   "thingArn": "hvac1:arn",
                   "thingName": "hvac1",
                   "thingTypeName": "hvac",
                   "version": 1
                },
                {
                    "attributes": {
                       "unit_id" : "2"
                    },
                    "thingArn": "hvac2:arn",
                    "thingName": "hvac2",
                    "thingTypeName": "hvac",
                    "version": 4
                 }
             ]
        }');

        $client = new MockHttpClient($response);
        $result = new ListThingsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new IotClient(), new ListThingsRequest([]));

        $expected = [
            new ThingAttribute(['thingName' => 'hvac1', 'thingArn' => 'hvac1:arn', 'thingTypeName' => 'hvac', 'version' => 1, 'attributes' => ['unit_id' => '1']]),
            new ThingAttribute(['thingName' => 'hvac2', 'thingArn' => 'hvac2:arn', 'thingTypeName' => 'hvac', 'version' => 4, 'attributes' => ['unit_id' => '2']]),
        ];
        self::assertEquals($expected, iterator_to_array($result->getThings()));
        self::assertSame(null, $result->getNextToken());
    }
}
