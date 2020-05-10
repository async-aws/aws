<?php

namespace AsyncAws\EventBridge\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\EventBridge\Input\PutEventsRequest;
use AsyncAws\EventBridge\ValueObject\PutEventsRequestEntry;

class PutEventsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new PutEventsRequest([
            'Entries' => [new PutEventsRequestEntry([
                'Time' => new \DateTimeImmutable(),
                'Source' => 'change me',
                'Resources' => ['change me'],
                'DetailType' => 'change me',
                'Detail' => 'change me',
                'EventBusName' => 'change me',
            ])],
        ]);

        // see https://docs.aws.amazon.com/eventbridge/latest/APIReference/API_PutEvents.html
        $expected = '
                    POST / HTTP/1.0
                    Content-Type: application/x-amz-json-1.1

                    {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
