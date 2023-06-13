<?php

namespace AsyncAws\Sns\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sns\Input\ListTopicsInput;
use AsyncAws\Sns\Result\ListTopicsResponse;
use AsyncAws\Sns\SnsClient;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListTopicsResponseTest extends TestCase
{
    public function testListTopicsResponse(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/sns/latest/api/API_ListTopics.html
        $response = new SimpleMockedResponse('<change>it</change>');

        $client = new MockHttpClient($response);
        $result = new ListTopicsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new SnsClient(), new ListTopicsInput([]));

        // self::assertTODO(expected, $result->getTopics());
        self::assertSame('changeIt', $result->getNextToken());
    }
}
