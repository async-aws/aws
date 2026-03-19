<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\ListEventSourceMappingsRequest;

class ListEventSourceMappingsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListEventSourceMappingsRequest([
            'EventSourceArn' => 'arn:aws:sqs:us-west-2:123456789012:mySQSqueue',
            'FunctionName' => 'my-function',
            'Marker' => 'xxyy',
            'MaxItems' => 10,
        ]);

        // see https://docs.aws.amazon.com/lambda/latest/APIReference/API_ListEventSourceMappings.html
        $expected = '
            GET /2015-03-31/event-source-mappings?EventSourceArn=arn%3Aaws%3Asqs%3Aus-west-2%3A123456789012%3AmySQSqueue&FunctionName=my-function&Marker=xxyy&MaxItems=10 HTTP/1.1
            Content-type: application/json
            Accept: application/json
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
