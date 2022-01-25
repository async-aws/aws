<?php

namespace AsyncAws\CloudWatchLogs;

use AsyncAws\CloudWatchLogs\Enum\OrderBy;
use AsyncAws\CloudWatchLogs\Exception\DataAlreadyAcceptedException;
use AsyncAws\CloudWatchLogs\Exception\InvalidParameterException;
use AsyncAws\CloudWatchLogs\Exception\InvalidSequenceTokenException;
use AsyncAws\CloudWatchLogs\Exception\LimitExceededException;
use AsyncAws\CloudWatchLogs\Exception\OperationAbortedException;
use AsyncAws\CloudWatchLogs\Exception\ResourceAlreadyExistsException;
use AsyncAws\CloudWatchLogs\Exception\ResourceNotFoundException;
use AsyncAws\CloudWatchLogs\Exception\ServiceUnavailableException;
use AsyncAws\CloudWatchLogs\Exception\UnrecognizedClientException;
use AsyncAws\CloudWatchLogs\Input\CreateLogGroupRequest;
use AsyncAws\CloudWatchLogs\Input\DescribeLogStreamsRequest;
use AsyncAws\CloudWatchLogs\Input\FilterLogEventsRequest;
use AsyncAws\CloudWatchLogs\Input\PutLogEventsRequest;
use AsyncAws\CloudWatchLogs\Result\DescribeLogStreamsResponse;
use AsyncAws\CloudWatchLogs\Result\FilterLogEventsResponse;
use AsyncAws\CloudWatchLogs\Result\PutLogEventsResponse;
use AsyncAws\CloudWatchLogs\ValueObject\InputLogEvent;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Result;

class CloudWatchLogsClient extends AbstractApi
{
    /**
     * Creates a log group with the specified name. You can create up to 20,000 log groups per account.
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_CreateLogGroup.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-logs-2014-03-28.html#createloggroup
     *
     * @param array{
     *   logGroupName: string,
     *   kmsKeyId?: string,
     *   tags?: array<string, string>,
     *   @region?: string,
     * }|CreateLogGroupRequest $input
     *
     * @throws InvalidParameterException
     * @throws ResourceAlreadyExistsException
     * @throws LimitExceededException
     * @throws OperationAbortedException
     * @throws ServiceUnavailableException
     */
    public function createLogGroup($input): Result
    {
        $input = CreateLogGroupRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateLogGroup', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterException' => InvalidParameterException::class,
            'ResourceAlreadyExistsException' => ResourceAlreadyExistsException::class,
            'LimitExceededException' => LimitExceededException::class,
            'OperationAbortedException' => OperationAbortedException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
        ]]));

        return new Result($response);
    }

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
     *
     * @throws InvalidParameterException
     * @throws ResourceNotFoundException
     * @throws ServiceUnavailableException
     */
    public function describeLogStreams($input): DescribeLogStreamsResponse
    {
        $input = DescribeLogStreamsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeLogStreams', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterException' => InvalidParameterException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
        ]]));

        return new DescribeLogStreamsResponse($response, $this, $input);
    }

    /**
     * Lists log events from the specified log group. You can list all the log events or filter the results using a filter
     * pattern, a time range, and the name of the log stream.
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_FilterLogEvents.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-logs-2014-03-28.html#filterlogevents
     *
     * @param array{
     *   logGroupName: string,
     *   logStreamNames?: string[],
     *   logStreamNamePrefix?: string,
     *   startTime?: string,
     *   endTime?: string,
     *   filterPattern?: string,
     *   nextToken?: string,
     *   limit?: int,
     *   interleaved?: bool,
     *   @region?: string,
     * }|FilterLogEventsRequest $input
     *
     * @throws InvalidParameterException
     * @throws ResourceNotFoundException
     * @throws ServiceUnavailableException
     */
    public function filterLogEvents($input): FilterLogEventsResponse
    {
        $input = FilterLogEventsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'FilterLogEvents', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterException' => InvalidParameterException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
        ]]));

        return new FilterLogEventsResponse($response, $this, $input);
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
     *
     * @throws InvalidParameterException
     * @throws InvalidSequenceTokenException
     * @throws DataAlreadyAcceptedException
     * @throws ResourceNotFoundException
     * @throws ServiceUnavailableException
     * @throws UnrecognizedClientException
     */
    public function putLogEvents($input): PutLogEventsResponse
    {
        $input = PutLogEventsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutLogEvents', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidSequenceTokenException' => InvalidSequenceTokenException::class,
            'DataAlreadyAcceptedException' => DataAlreadyAcceptedException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'UnrecognizedClientException' => UnrecognizedClientException::class,
        ]]));

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
            case 'us-iso-west-1':
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
            case 'us-east-1':
                return [
                    'endpoint' => 'https://logs.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'logs',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-2':
                return [
                    'endpoint' => 'https://logs.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
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
            case 'us-west-1':
                return [
                    'endpoint' => 'https://logs.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'logs',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-2':
                return [
                    'endpoint' => 'https://logs.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
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
