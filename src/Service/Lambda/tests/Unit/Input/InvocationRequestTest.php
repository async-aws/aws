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
    /**
     * @var InvocationRequest
     */
    private $input;

    public function setUp(): void
    {
        $this->input = new InvocationRequest([
            'FunctionName' => 'MyFunction',
            'InvocationType' => 'Event',
            'LogType' => 'Tail',
            'ClientContext' => 'MyApp',
            'Payload' => '{"name": "jderusse"}',
            'Qualifier' => '1',
        ]);
        parent::setUp();
    }

    public function testRequestBody(): void
    {
        $expected = '{
            "name": "jderusse"
        }';

        self::assertJsonStringEqualsJsonString($expected, $this->input->request()->getBody()->stringify());
    }

    public function testRequestUri(): void
    {
        $expected = '/2015-03-31/functions/MyFunction/invocations';

        self::assertSame($expected, $this->input->request()->getUri());
    }
}
