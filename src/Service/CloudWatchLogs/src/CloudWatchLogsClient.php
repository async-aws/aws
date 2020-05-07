<?php

namespace AsyncAws\CloudWatchLogs;

use AsyncAws\CloudWatchLogs\Input\DescribeLogStreamsRequest;
use AsyncAws\CloudWatchLogs\Input\PutLogEventsRequest;
use AsyncAws\CloudWatchLogs\Result\DescribeLogStreamsResponse;
use AsyncAws\CloudWatchLogs\Result\PutLogEventsResponse;
use AsyncAws\CloudWatchLogs\ValueObject\LogStream;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\RequestContext;

class CloudWatchLogsClient extends AbstractApi
{
    /**
     * Lists the log streams for the specified log group. You can list all the log streams or filter the results by prefix.
     * You can also control how the results are ordered.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-logs-2014-03-28.html#describelogstreams
     *
     * @param array{
     *   logGroupName: string,
     *   logStreamNamePrefix?: string,
     *   orderBy?: \AsyncAws\CloudWatchLogs\Enum\OrderBy::*,
     *   descending?: bool,
     *   nextToken?: string,
     *   limit?: int,
     *   @region?: string,
     * }|DescribeLogStreamsRequest $input
     *
     * @return \Traversable<LogStream> & DescribeLogStreamsResponse
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
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-logs-2014-03-28.html#putlogevents
     *
     * @param array{
     *   logGroupName: string,
     *   logStreamName: string,
     *   logEvents: \AsyncAws\CloudWatchLogs\ValueObject\InputLogEvent[],
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

    protected function getServiceCode(): string
    {
        return 'logs';
    }

    protected function getSignatureScopeName(): string
    {
        return 'logs';
    }

    protected function getSignatureVersion(): string
    {
        return 'v4';
    }
}
