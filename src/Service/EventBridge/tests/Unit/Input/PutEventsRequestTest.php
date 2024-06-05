<?php

namespace AsyncAws\EventBridge\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\EventBridge\Input\PutEventsRequest;
use AsyncAws\EventBridge\ValueObject\PutEventsRequestEntry;

class PutEventsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PutEventsRequest([
            'Entries' => [new PutEventsRequestEntry([
                'Time' => new \DateTimeImmutable('2020-05-10 12:14:00', new \DateTimeZone('UTC')),
                'Source' => 'com.mycompany.myapp',
                'Resources' => ['resource1', 'resource2'],
                'DetailType' => 'myDetailType',
                'Detail' => json_encode(['key1' => 'value1', 'key2' => 'value2']),
            ])],
        ]);

        // see https://docs.aws.amazon.com/eventbridge/latest/APIReference/API_PutEvents.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: AWSEvents.PutEvents
Accept: application/json

{
   "Entries":[
      {
         "Source":"com.mycompany.myapp",
         "Detail":"{\"key1\":\"value1\",\"key2\":\"value2\"}",
         "Resources":[
            "resource1",
            "resource2"
         ],
         "DetailType":"myDetailType",
         "Time": 1589112840
      }
   ]
}
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
