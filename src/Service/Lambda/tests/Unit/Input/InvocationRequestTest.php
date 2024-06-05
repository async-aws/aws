<?php

namespace AsyncAws\Lambda\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\InvocationRequest;

/**
 * @see example-1.json from SDK
 * @see https://docs.aws.amazon.com/lambda/latest/dg/API_Invoke.html
 */
class InvocationRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new InvocationRequest([
            'FunctionName' => 'MyFunction',
            'InvocationType' => 'Event',
            'LogType' => 'Tail',
            'ClientContext' => 'MyApp',
            'Payload' => '{"name": "jderusse"}',
            'Qualifier' => '1',
        ]);

        $expected = '
            POST /2015-03-31/functions/MyFunction/invocations?Qualifier=1 HTTP/1.0
            Content-type: application/json
            Accept: application/json
            x-amz-invocation-type: Event
            x-amz-log-type: Tail
            x-amz-client-context: MyApp


            {
                "name": "jderusse"
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
