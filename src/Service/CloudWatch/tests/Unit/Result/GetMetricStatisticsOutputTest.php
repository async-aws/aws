<?php

namespace AsyncAws\CloudWatch\Tests\Unit\Result;

use AsyncAws\CloudWatch\Result\GetMetricStatisticsOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetMetricStatisticsOutputTest extends TestCase
{
    public function testGetMetricStatisticsOutput(): void
    {
        // see https://docs.aws.amazon.com/cloudwatch/latest/APIReference/API_GetMetricStatistics.html
        $response = new SimpleMockedResponse('<change>it</change>');

        $client = new MockHttpClient($response);
        $result = new GetMetricStatisticsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('changeIt', $result->getLabel());
        // self::assertTODO(expected, $result->getDatapoints());
    }
}
