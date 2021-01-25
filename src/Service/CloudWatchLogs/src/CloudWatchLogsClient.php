<?php

namespace AsyncAws\CloudWatchLogs;

use AsyncAws\CloudWatchLogs\Enum\OrderBy;
use AsyncAws\CloudWatchLogs\Input\DescribeLogStreamsRequest;
use AsyncAws\CloudWatchLogs\Input\PutLogEventsRequest;
use AsyncAws\CloudWatchLogs\Result\DescribeLogStreamsResponse;
use AsyncAws\CloudWatchLogs\Result\PutLogEventsResponse;
use AsyncAws\CloudWatchLogs\ValueObject\InputLogEvent;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;

class CloudWatchLogsClient extends AbstractApi
{
    /**
     * Lists the log streams for the specified log group. You can list all the log streams or filter the results by prefix.
     * You can also control how the results are ordered.
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_DescribeLogStreams.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-logs-2014-03-28.html#describelogstreams
     *
     * @param array{
     *   logGroupName: string,
     *   logStreamNamePrefix?: string,
     *   orderBy?: OrderBy::*,
     *   descending?: bool,
     *   nextToken?: string,
     *   limit?: int,
     *   @region?: string,
     * }|DescribeLogStreamsRequest $input
     */
    public function describeLogStreams($input): DescribeLogStreamsResponse
    {
        $input = DescribeLogStreamsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeLogStreams', 'region' => $input->getRegion()]));

        return new DescribeLogStreamsResponse($response, $this, $input);
    }

    /**
     * Uploads a batch of log events to the specified log stream.
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_PutLogEvents.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-logs-2014-03-28.html#putlogevents
     *
     * @param array{
     *   logGroupName: string,
     *   logStreamName: string,
     *   logEvents: InputLogEvent[],
     *   sequenceToken?: string,
     *   @region?: string,
     * }|PutLogEventsRequest $input
     */
    public function putLogEvents($input): PutLogEventsResponse
    {
        $input = PutLogEventsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutLogEvents', 'region' => $input->getRegion()]));

        return new PutLogEventsResponse($response);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new JsonRpcAwsErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        switch ($region) {
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://logs.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'logs',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
                return [
                    'endpoint' => "https://logs.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'logs',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => "https://logs.$region.sc2s.sgov.gov",
                    'signRegion' => $region,
                    'signService' => 'logs',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://logs-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'logs',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://logs-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'logs',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://logs-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'logs',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://logs-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'logs',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1':
                return [
                    'endpoint' => 'https://logs.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'logs',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-west-1':
                return [
                    'endpoint' => 'https://logs.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'logs',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://logs.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'logs',
            'signVersions' => ['v4'],
        ];
    }
}
