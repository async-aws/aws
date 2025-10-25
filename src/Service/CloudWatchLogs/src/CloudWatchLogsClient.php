<?php

namespace AsyncAws\CloudWatchLogs;

use AsyncAws\CloudWatchLogs\Enum\LogGroupClass;
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
use AsyncAws\CloudWatchLogs\Input\CreateLogStreamRequest;
use AsyncAws\CloudWatchLogs\Input\DescribeLogStreamsRequest;
use AsyncAws\CloudWatchLogs\Input\FilterLogEventsRequest;
use AsyncAws\CloudWatchLogs\Input\PutLogEventsRequest;
use AsyncAws\CloudWatchLogs\Result\DescribeLogStreamsResponse;
use AsyncAws\CloudWatchLogs\Result\FilterLogEventsResponse;
use AsyncAws\CloudWatchLogs\Result\PutLogEventsResponse;
use AsyncAws\CloudWatchLogs\ValueObject\Entity;
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
     * Creates a log group with the specified name. You can create up to 1,000,000 log groups per Region per account.
     *
     * You must use the following guidelines when naming a log group:
     *
     * - Log group names must be unique within a Region for an Amazon Web Services account.
     * - Log group names can be between 1 and 512 characters long.
     * - Log group names consist of the following characters: a-z, A-Z, 0-9, '_' (underscore), '-' (hyphen), '/' (forward
     *   slash), '.' (period), and '#' (number sign)
     * - Log group names can't start with the string `aws/`
     *
     * When you create a log group, by default the log events in the log group do not expire. To set a retention policy so
     * that events expire and are deleted after a specified time, use PutRetentionPolicy [^1].
     *
     * If you associate an KMS key with the log group, ingested data is encrypted using the KMS key. This association is
     * stored as long as the data encrypted with the KMS key is still within CloudWatch Logs. This enables CloudWatch Logs
     * to decrypt this data whenever it is requested.
     *
     * If you attempt to associate a KMS key with the log group but the KMS key does not exist or the KMS key is disabled,
     * you receive an `InvalidParameterException` error.
     *
     * ! CloudWatch Logs supports only symmetric KMS keys. Do not associate an asymmetric KMS key with your log group. For
     * ! more information, see Using Symmetric and Asymmetric Keys [^2].
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_PutRetentionPolicy.html
     * [^2]: https://docs.aws.amazon.com/kms/latest/developerguide/symmetric-asymmetric.html
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_CreateLogGroup.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-logs-2014-03-28.html#createloggroup
     *
     * @param array{
     *   logGroupName: string,
     *   kmsKeyId?: string|null,
     *   tags?: array<string, string>|null,
     *   logGroupClass?: LogGroupClass::*|null,
     *   '@region'?: string|null,
     * }|CreateLogGroupRequest $input
     *
     * @throws InvalidParameterException
     * @throws LimitExceededException
     * @throws OperationAbortedException
     * @throws ResourceAlreadyExistsException
     * @throws ServiceUnavailableException
     */
    public function createLogGroup($input): Result
    {
        $input = CreateLogGroupRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateLogGroup', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterException' => InvalidParameterException::class,
            'LimitExceededException' => LimitExceededException::class,
            'OperationAbortedException' => OperationAbortedException::class,
            'ResourceAlreadyExistsException' => ResourceAlreadyExistsException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Creates a log stream for the specified log group. A log stream is a sequence of log events that originate from a
     * single source, such as an application instance or a resource that is being monitored.
     *
     * There is no limit on the number of log streams that you can create for a log group. There is a limit of 50 TPS on
     * `CreateLogStream` operations, after which transactions are throttled.
     *
     * You must use the following guidelines when naming a log stream:
     *
     * - Log stream names must be unique within the log group.
     * - Log stream names can be between 1 and 512 characters long.
     * - Don't use ':' (colon) or '*' (asterisk) characters.
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_CreateLogStream.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-logs-2014-03-28.html#createlogstream
     *
     * @param array{
     *   logGroupName: string,
     *   logStreamName: string,
     *   '@region'?: string|null,
     * }|CreateLogStreamRequest $input
     *
     * @throws InvalidParameterException
     * @throws ResourceAlreadyExistsException
     * @throws ResourceNotFoundException
     * @throws ServiceUnavailableException
     */
    public function createLogStream($input): Result
    {
        $input = CreateLogStreamRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateLogStream', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterException' => InvalidParameterException::class,
            'ResourceAlreadyExistsException' => ResourceAlreadyExistsException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Lists the log streams for the specified log group. You can list all the log streams or filter the results by prefix.
     * You can also control how the results are ordered.
     *
     * You can specify the log group to search by using either `logGroupIdentifier` or `logGroupName`. You must include one
     * of these two parameters, but you can't include both.
     *
     * This operation has a limit of 25 transactions per second, after which transactions are throttled.
     *
     * If you are using CloudWatch cross-account observability, you can use this operation in a monitoring account and view
     * data from the linked source accounts. For more information, see CloudWatch cross-account observability [^1].
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/CloudWatch-Unified-Cross-Account.html
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_DescribeLogStreams.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-logs-2014-03-28.html#describelogstreams
     *
     * @param array{
     *   logGroupName?: string|null,
     *   logGroupIdentifier?: string|null,
     *   logStreamNamePrefix?: string|null,
     *   orderBy?: OrderBy::*|null,
     *   descending?: bool|null,
     *   nextToken?: string|null,
     *   limit?: int|null,
     *   '@region'?: string|null,
     * }|DescribeLogStreamsRequest $input
     *
     * @throws InvalidParameterException
     * @throws ResourceNotFoundException
     * @throws ServiceUnavailableException
     */
    public function describeLogStreams($input = []): DescribeLogStreamsResponse
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
     * Lists log events from the specified log group. You can list all the log events or filter the results using one or
     * more of the following:
     *
     * - A filter pattern
     * - A time range
     * - The log stream name, or a log stream name prefix that matches multiple log streams
     *
     * You must have the `logs:FilterLogEvents` permission to perform this operation.
     *
     * You can specify the log group to search by using either `logGroupIdentifier` or `logGroupName`. You must include one
     * of these two parameters, but you can't include both.
     *
     * `FilterLogEvents` is a paginated operation. Each page returned can contain up to 1 MB of log events or up to 10,000
     * log events. A returned page might only be partially full, or even empty. For example, if the result of a query would
     * return 15,000 log events, the first page isn't guaranteed to have 10,000 log events even if they all fit into 1 MB.
     *
     * Partially full or empty pages don't necessarily mean that pagination is finished. If the results include a
     * `nextToken`, there might be more log events available. You can return these additional log events by providing the
     * nextToken in a subsequent `FilterLogEvents` operation. If the results don't include a `nextToken`, then pagination is
     * finished.
     *
     * Specifying the `limit` parameter only guarantees that a single page doesn't return more log events than the specified
     * limit, but it might return fewer events than the limit. This is the expected API behavior.
     *
     * The returned log events are sorted by event timestamp, the timestamp when the event was ingested by CloudWatch Logs,
     * and the ID of the `PutLogEvents` request.
     *
     * If you are using CloudWatch cross-account observability, you can use this operation in a monitoring account and view
     * data from the linked source accounts. For more information, see CloudWatch cross-account observability [^1].
     *
     * > If you are using log transformation [^2], the `FilterLogEvents` operation returns only the original versions of log
     * > events, before they were transformed. To view the transformed versions, you must use a CloudWatch Logs query. [^3]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/CloudWatch-Unified-Cross-Account.html
     * [^2]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/logs/CloudWatch-Logs-Transformation.html
     * [^3]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/logs/AnalyzingLogData.html
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_FilterLogEvents.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-logs-2014-03-28.html#filterlogevents
     *
     * @param array{
     *   logGroupName?: string|null,
     *   logGroupIdentifier?: string|null,
     *   logStreamNames?: string[]|null,
     *   logStreamNamePrefix?: string|null,
     *   startTime?: int|null,
     *   endTime?: int|null,
     *   filterPattern?: string|null,
     *   nextToken?: string|null,
     *   limit?: int|null,
     *   interleaved?: bool|null,
     *   unmask?: bool|null,
     *   '@region'?: string|null,
     * }|FilterLogEventsRequest $input
     *
     * @throws InvalidParameterException
     * @throws ResourceNotFoundException
     * @throws ServiceUnavailableException
     */
    public function filterLogEvents($input = []): FilterLogEventsResponse
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
     * ! The sequence token is now ignored in `PutLogEvents` actions. `PutLogEvents` actions are always accepted and never
     * ! return `InvalidSequenceTokenException` or `DataAlreadyAcceptedException` even if the sequence token is not valid.
     * ! You can use parallel `PutLogEvents` actions on the same log stream.
     *
     * The batch of events must satisfy the following constraints:
     *
     * - The maximum batch size is 1,048,576 bytes. This size is calculated as the sum of all event messages in UTF-8, plus
     *   26 bytes for each log event.
     * - Events more than 2 hours in the future are rejected while processing remaining valid events.
     * - Events older than 14 days or preceding the log group's retention period are rejected while processing remaining
     *   valid events.
     * - The log events in the batch must be in chronological order by their timestamp. The timestamp is the time that the
     *   event occurred, expressed as the number of milliseconds after `Jan 1, 1970 00:00:00 UTC`. (In Amazon Web Services
     *   Tools for PowerShell and the Amazon Web Services SDK for .NET, the timestamp is specified in .NET format:
     *   `yyyy-mm-ddThh:mm:ss`. For example, `2017-09-15T13:45:30`.)
     * - A batch of log events in a single request must be in a chronological order. Otherwise, the operation fails.
     * - Each log event can be no larger than 1 MB.
     * - The maximum number of log events in a batch is 10,000.
     * - For valid events (within 14 days in the past to 2 hours in future), the time span in a single batch cannot exceed
     *   24 hours. Otherwise, the operation fails.
     *
     * ! The quota of five requests per second per log stream has been removed. Instead, `PutLogEvents` actions are
     * ! throttled based on a per-second per-account quota. You can request an increase to the per-second throttling quota
     * ! by using the Service Quotas service.
     *
     * If a call to `PutLogEvents` returns "UnrecognizedClientException" the most likely cause is a non-valid Amazon Web
     * Services access key ID or secret key.
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_PutLogEvents.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-logs-2014-03-28.html#putlogevents
     *
     * @param array{
     *   logGroupName: string,
     *   logStreamName: string,
     *   logEvents: array<InputLogEvent|array>,
     *   sequenceToken?: string|null,
     *   entity?: Entity|array|null,
     *   '@region'?: string|null,
     * }|PutLogEventsRequest $input
     *
     * @throws DataAlreadyAcceptedException
     * @throws InvalidParameterException
     * @throws InvalidSequenceTokenException
     * @throws ResourceNotFoundException
     * @throws ServiceUnavailableException
     * @throws UnrecognizedClientException
     */
    public function putLogEvents($input): PutLogEventsResponse
    {
        $input = PutLogEventsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutLogEvents', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'DataAlreadyAcceptedException' => DataAlreadyAcceptedException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidSequenceTokenException' => InvalidSequenceTokenException::class,
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
            case 'fips-ca-central-1':
                return [
                    'endpoint' => 'https://logs-fips.ca-central-1.amazonaws.com',
                    'signRegion' => 'ca-central-1',
                    'signService' => 'logs',
                    'signVersions' => ['v4'],
                ];
            case 'fips-ca-west-1':
                return [
                    'endpoint' => 'https://logs-fips.ca-west-1.amazonaws.com',
                    'signRegion' => 'ca-west-1',
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
            case 'fips-us-gov-east-1':
                return [
                    'endpoint' => 'https://logs.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'logs',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://logs.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
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
            case 'us-isob-west-1':
                return [
                    'endpoint' => "https://logs.$region.sc2s.sgov.gov",
                    'signRegion' => $region,
                    'signService' => 'logs',
                    'signVersions' => ['v4'],
                ];
            case 'us-isof-east-1':
            case 'us-isof-south-1':
                return [
                    'endpoint' => "https://logs.$region.csp.hci.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'logs',
                    'signVersions' => ['v4'],
                ];
            case 'eu-isoe-west-1':
                return [
                    'endpoint' => 'https://logs.eu-isoe-west-1.cloud.adc-e.uk',
                    'signRegion' => 'eu-isoe-west-1',
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
