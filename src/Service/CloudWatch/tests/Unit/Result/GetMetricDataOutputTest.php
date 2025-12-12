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
        // see https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_GetMetricData.html
        $response = new SimpleMockedResponse('{
            "MetricDataResults": [
                {
                    "Id": "m1",
                    "Label": "Unhealthy Behind Load Balancer",
                    "StatusCode": "Complete",
                    "Timestamps": [
                        1637074200,
                        1637073900,
                        1637073600
                    ],
                    "Values": [
                        0,
                        0,
                        0
                    ]
                },
                {
                    "Id": "q1",
                    "Label": "Cluster CpuUtilization",
                    "StatusCode": "Complete",
                    "Timestamps": [
                        1637074245,
                        1637073945,
                        1637073645
                    ],
                    "Values": [
                        1.2158469945359334,
                        0.8678863271635757,
                        0.7201860957623283
                    ]
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new GetMetricDataOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new CloudWatchClient(), new GetMetricDataInput([]));

        $metricDataResults = [...$result->getMetricDataResults(true)];
        self::assertCount(2, $metricDataResults);
        self::assertSame('m1', $metricDataResults[0]->getId());
        self::assertCount(3, $metricDataResults[0]->getTimestamps());
        self::assertSame('2021-11-16T14:45:00+00:00', $metricDataResults[0]->getTimestamps()[1]->format(\DATE_ATOM));
    }
}
