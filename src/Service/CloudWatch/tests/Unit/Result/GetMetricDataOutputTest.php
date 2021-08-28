<?php

namespace AsyncAws\CloudWatch\Tests\Unit\Result;

use AsyncAws\CloudWatch\CloudWatchClient;
use AsyncAws\CloudWatch\Input\GetMetricDataInput;
use AsyncAws\CloudWatch\Result\GetMetricDataOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetMetricDataOutputTest extends TestCase
{
    public function testGetMetricDataOutput(): void
    {
        // see https://docs.aws.amazon.com/cloudwatch/latest/APIReference/API_GetMetricData.html
        $response = new SimpleMockedResponse('<Result><GetMetricDataResult><NextToken>foobar</NextToken></GetMetricDataResult></Result>');

        $client = new MockHttpClient($response);
        $result = new GetMetricDataOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new CloudWatchClient(), new GetMetricDataInput([]));

        // self::assertTODO(expected, $result->getMetricDataResults());
        self::assertSame('foobar', $result->getNextToken());
        // self::assertTODO(expected, $result->getMessages());
    }
}
