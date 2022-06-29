<?php

namespace AsyncAws\Iot\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iot\Input\ListThingTypesRequest;
use AsyncAws\Iot\IotClient;
use AsyncAws\Iot\Result\ListThingTypesResponse;
use AsyncAws\Iot\ValueObject\ThingTypeDefinition;
use DateTimeImmutable;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListThingTypesResponseTest extends TestCase
{
    public function testListThingTypesResponse(): void
    {
        // see https://docs.aws.amazon.com/iot/latest/apireference/API_ListThingTypes.html
        $response = new SimpleMockedResponse('{
            "thingTypes": [
                {
                   "thingTypeArn": "hvac:arn",
                   "thingTypeMetadata": {
                      "creationDate": 1656427031.774,
                      "deprecated": false,
                      "deprecationDate":null
                    },
                   "thingTypeName": "hvac",
                   "thingTypeProperties": {
                      "searchableAttributes": [ "energy" ],
                      "thingTypeDescription": "HVAC"
                   }
                },
                {
                    "thingTypeArn": "sensor:arn",
                    "thingTypeMetadata": {
                       "creationDate": 1656427031.774,
                       "deprecated": true,
                       "deprecationDate": 1656427031.774
                    },
                    "thingTypeName": "sensor",
                    "thingTypeProperties": {
                       "thingTypeDescription": "All kind of sensor"
                    }
                 }
             ]
        }');

        $client = new MockHttpClient($response);
        $result = new ListThingTypesResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new IotClient(), new ListThingTypesRequest([]));

        $expected = [
            new ThingTypeDefinition(['thingTypeName' => 'hvac', 'thingTypeArn' => 'hvac:arn', 'thingTypeMetadata' => ['creationDate' => new DateTimeImmutable('2022-06-28T14:37:11.774000+0000'), 'deprecated' => false], 'thingTypeProperties' => ['searchableAttributes' => ['energy'], 'thingTypeDescription' => 'HVAC']]),
            new ThingTypeDefinition(['thingTypeName' => 'sensor', 'thingTypeArn' => 'sensor:arn', 'thingTypeMetadata' => ['creationDate' => new DateTimeImmutable('2022-06-28T14:37:11.774000+0000'), 'deprecated' => true, 'deprecationDate' => new DateTimeImmutable('2022-06-28T14:37:11.774000+0000')], 'thingTypeProperties' => ['thingTypeDescription' => 'All kind of sensor']]),
        ];
        self::assertEquals($expected, iterator_to_array($result->getThingTypes()));
        self::assertSame(null, $result->getNextToken());
    }
}
