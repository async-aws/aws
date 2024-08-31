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
use AsyncAws\Core\Exception\UnsupportedRegion;
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
     *   kmsKeyId?: null|string,
     *   tags?: null|array<string, string>,
     *   logGroupClass?: null|LogGroupClass::*,
     *   '@region'?: string|null,
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
     * This operation has a limit of five transactions per second, after which transactions are throttled.
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
     *   logGroupName?: null|string,
     *   logGroupIdentifier?: null|string,
     *   logStreamNamePrefix?: null|string,
     *   orderBy?: null|OrderBy::*,
     *   descending?: null|bool,
     *   nextToken?: null|string,
     *   limit?: null|int,
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
     * Lists log events from the specified log group. You can list all the log events or filter the results using a filter
     * pattern, a time range, and the name of the log stream.
     *
     * You must have the `logs:FilterLogEvents` permission to perform this operation.
     *
     * You can specify the log group to search by using either `logGroupIdentifier` or `logGroupName`. You must include one
     * of these two parameters, but you can't include both.
     *
     * By default, this operation returns as many log events as can fit in 1 MB (up to 10,000 log events) or all the events
     * found within the specified time range. If the results include a token, that means there are more log events
     * available. You can get additional results by specifying the token in a subsequent call. This operation can return
     * empty results while there are more log events available through the token.
     *
     * The returned log events are sorted by event timestamp, the timestamp when the event was ingested by CloudWatch Logs,
     * and the ID of the `PutLogEvents` request.
     *
     * If you are using CloudWatch cross-account observability, you can use this operation in a monitoring account and view
     * data from the linked source accounts. For more information, see CloudWatch cross-account observability [^1].
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/CloudWatch-Unified-Cross-Account.html
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_FilterLogEvents.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-logs-2014-03-28.html#filterlogevents
     *
     * @param array{
     *   logGroupName?: null|string,
     *   logGroupIdentifier?: null|string,
     *   logStreamNames?: null|string[],
     *   logStreamNamePrefix?: null|string,
     *   startTime?: null|int,
     *   endTime?: null|int,
     *   filterPattern?: null|string,
     *   nextToken?: null|string,
     *   limit?: null|int,
     *   interleaved?: null|bool,
     *   unmask?: null|bool,
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
     * - None of the log events in the batch can be more than 2 hours in the future.
     * - None of the log events in the batch can be more than 14 days in the past. Also, none of the log events can be from
     *   earlier than the retention period of the log group.
     * - The log events in the batch must be in chronological order by their timestamp. The timestamp is the time that the
     *   event occurred, expressed as the number of milliseconds after `Jan 1, 1970 00:00:00 UTC`. (In Amazon Web Services
     *   Tools for PowerShell and the Amazon Web Services SDK for .NET, the timestamp is specified in .NET format:
     *   `yyyy-mm-ddThh:mm:ss`. For example, `2017-09-15T13:45:30`.)
     * - A batch of log events in a single request cannot span more than 24 hours. Otherwise, the operation fails.
     * - Each log event can be no larger than 256 KB.
     * - The maximum number of log events in a batch is 10,000.
     * -
     * - ! The quota of five requests per second per log stream has been removed. Instead, `PutLogEvents` actions are
     * -   throttled based on a per-second per-account quota. You can request an increase to the per-second throttling quota
     * -   by using the Service Quotas service.
     * -
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
     *   sequenceToken?: null|string,
     *   entity?: null|Entity|array,
     *   '@region'?: string|null,
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
            case 'af-south-1':
            case 'ap-east-1':
            case 'ap-northeast-1':
            case 'ap-northeast-2':
            case 'ap-northeast-3':
            case 'ap-south-1':
            case 'ap-south-2':
            case 'ap-southeast-1':
            case 'ap-southeast-2':
            case 'ap-southeast-3':
            case 'ap-southeast-4':
            case 'ap-southeast-5':
            case 'ca-central-1':
            case 'ca-west-1':
            case 'eu-central-1':
            case 'eu-central-2':
            case 'eu-north-1':
            case 'eu-south-1':
            case 'eu-south-2':
            case 'eu-west-1':
            case 'eu-west-2':
            case 'eu-west-3':
            case 'il-central-1':
            case 'me-central-1':
            case 'me-south-1':
            case 'sa-east-1':
            case 'us-east-1':
            case 'us-east-2':
            case 'us-west-1':
            case 'us-west-2':
                return [
                    'endpoint' => "https://logs.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'logs',
                    'signVersions' => ['v4'],
                ];
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
            case 'us-gov-east-1':
                return [
                    'endpoint' => 'https://logs.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'logs',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
            case 'us-gov-west-1':
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
                return [
                    'endpoint' => 'https://logs.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'logs',
                    'signVersions' => ['v4'],
                ];
        }

        throw new UnsupportedRegion(\sprintf('The region "%s" is not supported by "CloudWatchLogs".', $region));
    }
}
