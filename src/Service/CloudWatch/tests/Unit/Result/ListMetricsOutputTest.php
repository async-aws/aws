<?php

namespace AsyncAws\CloudWatch\Tests\Unit\Result;

use AsyncAws\CloudWatch\CloudWatchClient;
use AsyncAws\CloudWatch\Input\ListMetricsInput;
use AsyncAws\CloudWatch\Result\ListMetricsOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListMetricsOutputTest extends TestCase
{
    public function testListMetricsOutput(): void
    {
        // see https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_ListMetrics.html
        $response = new SimpleMockedResponse('{
                "NextToken": "foobar"
        }');

        $client = new MockHttpClient($response);
        $result = new ListMetricsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new CloudWatchClient(), new ListMetricsInput([]));

        // self::assertTODO(expected, $result->getMetrics());
        self::assertSame('foobar', $result->getNextToken());
    }
}
