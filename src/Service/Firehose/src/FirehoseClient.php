<?php

namespace AsyncAws\Firehose;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\Firehose\Exception\InvalidArgumentException;
use AsyncAws\Firehose\Exception\InvalidKMSResourceException;
use AsyncAws\Firehose\Exception\InvalidSourceException;
use AsyncAws\Firehose\Exception\ResourceNotFoundException;
use AsyncAws\Firehose\Exception\ServiceUnavailableException;
use AsyncAws\Firehose\Input\PutRecordBatchInput;
use AsyncAws\Firehose\Input\PutRecordInput;
use AsyncAws\Firehose\Result\PutRecordBatchOutput;
use AsyncAws\Firehose\Result\PutRecordOutput;
use AsyncAws\Firehose\ValueObject\Record;

class FirehoseClient extends AbstractApi
{
    /**
     * Writes a single data record into an Firehose stream. To write multiple data records into a Firehose stream, use
     * PutRecordBatch. Applications using these operations are referred to as producers.
     *
     * By default, each Firehose stream can take in up to 2,000 transactions per second, 5,000 records per second, or 5 MB
     * per second. If you use PutRecord and PutRecordBatch, the limits are an aggregate across these two operations for each
     * Firehose stream. For more information about limits and how to request an increase, see Amazon Firehose Limits [^1].
     *
     * Firehose accumulates and publishes a particular metric for a customer account in one minute intervals. It is possible
     * that the bursts of incoming bytes/records ingested to a Firehose stream last only for a few seconds. Due to this, the
     * actual spikes in the traffic might not be fully visible in the customer's 1 minute CloudWatch metrics.
     *
     * You must specify the name of the Firehose stream and the data record when using PutRecord. The data record consists
     * of a data blob that can be up to 1,000 KiB in size, and any kind of data. For example, it can be a segment from a log
     * file, geographic location data, website clickstream data, and so on.
     *
     * For multi record de-aggregation, you can not put more than 500 records even if the data blob length is less than 1000
     * KiB. If you include more than 500 records, the request succeeds but the record de-aggregation doesn't work as
     * expected and transformation lambda is invoked with the complete base64 encoded data blob instead of de-aggregated
     * base64 decoded records.
     *
     * Firehose buffers records before delivering them to the destination. To disambiguate the data blobs at the
     * destination, a common solution is to use delimiters in the data, such as a newline (`\n`) or some other character
     * unique within the data. This allows the consumer application to parse individual data items when reading the data
     * from the destination.
     *
     * The `PutRecord` operation returns a `RecordId`, which is a unique string assigned to each record. Producer
     * applications can use this ID for purposes such as auditability and investigation.
     *
     * If the `PutRecord` operation throws a `ServiceUnavailableException`, the API is automatically reinvoked (retried) 3
     * times. If the exception persists, it is possible that the throughput limits have been exceeded for the Firehose
     * stream.
     *
     * Re-invoking the Put API operations (for example, PutRecord and PutRecordBatch) can result in data duplicates. For
     * larger data assets, allow for a longer time out before retrying Put API operations.
     *
     * Data records sent to Firehose are stored for 24 hours from the time they are added to a Firehose stream as it tries
     * to send the records to the destination. If the destination is unreachable for more than 24 hours, the data is no
     * longer available.
     *
     * ! Don't concatenate two or more base64 strings to form the data fields of your records. Instead, concatenate the raw
     * ! data, then perform base64 encoding.
     *
     * [^1]: https://docs.aws.amazon.com/firehose/latest/dev/limits.html
     *
     * @see https://docs.aws.amazon.com/firehose/latest/APIReference/API_PutRecord.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-firehose-2015-08-04.html#putrecord
     *
     * @param array{
     *   DeliveryStreamName: string,
     *   Record: Record|array,
     *   '@region'?: string|null,
     * }|PutRecordInput $input
     *
     * @throws InvalidArgumentException
     * @throws InvalidKMSResourceException
     * @throws InvalidSourceException
     * @throws ResourceNotFoundException
     * @throws ServiceUnavailableException
     */
    public function putRecord($input): PutRecordOutput
    {
        $input = PutRecordInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutRecord', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidArgumentException' => InvalidArgumentException::class,
            'InvalidKMSResourceException' => InvalidKMSResourceException::class,
            'InvalidSourceException' => InvalidSourceException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
        ]]));

        return new PutRecordOutput($response);
    }

    /**
     * Writes multiple data records into a Firehose stream in a single call, which can achieve higher throughput per
     * producer than when writing single records. To write single data records into a Firehose stream, use PutRecord.
     * Applications using these operations are referred to as producers.
     *
     * Firehose accumulates and publishes a particular metric for a customer account in one minute intervals. It is possible
     * that the bursts of incoming bytes/records ingested to a Firehose stream last only for a few seconds. Due to this, the
     * actual spikes in the traffic might not be fully visible in the customer's 1 minute CloudWatch metrics.
     *
     * For information about service quota, see Amazon Firehose Quota [^1].
     *
     * Each PutRecordBatch request supports up to 500 records. Each record in the request can be as large as 1,000 KB
     * (before base64 encoding), up to a limit of 4 MB for the entire request. These limits cannot be changed.
     *
     * You must specify the name of the Firehose stream and the data record when using PutRecord. The data record consists
     * of a data blob that can be up to 1,000 KB in size, and any kind of data. For example, it could be a segment from a
     * log file, geographic location data, website clickstream data, and so on.
     *
     * For multi record de-aggregation, you can not put more than 500 records even if the data blob length is less than 1000
     * KiB. If you include more than 500 records, the request succeeds but the record de-aggregation doesn't work as
     * expected and transformation lambda is invoked with the complete base64 encoded data blob instead of de-aggregated
     * base64 decoded records.
     *
     * Firehose buffers records before delivering them to the destination. To disambiguate the data blobs at the
     * destination, a common solution is to use delimiters in the data, such as a newline (`\n`) or some other character
     * unique within the data. This allows the consumer application to parse individual data items when reading the data
     * from the destination.
     *
     * The PutRecordBatch response includes a count of failed records, `FailedPutCount`, and an array of responses,
     * `RequestResponses`. Even if the PutRecordBatch call succeeds, the value of `FailedPutCount` may be greater than 0,
     * indicating that there are records for which the operation didn't succeed. Each entry in the `RequestResponses` array
     * provides additional information about the processed record. It directly correlates with a record in the request array
     * using the same ordering, from the top to the bottom. The response array always includes the same number of records as
     * the request array. `RequestResponses` includes both successfully and unsuccessfully processed records. Firehose tries
     * to process all records in each PutRecordBatch request. A single record failure does not stop the processing of
     * subsequent records.
     *
     * A successfully processed record includes a `RecordId` value, which is unique for the record. An unsuccessfully
     * processed record includes `ErrorCode` and `ErrorMessage` values. `ErrorCode` reflects the type of error, and is one
     * of the following values: `ServiceUnavailableException` or `InternalFailure`. `ErrorMessage` provides more detailed
     * information about the error.
     *
     * If there is an internal server error or a timeout, the write might have completed or it might have failed. If
     * `FailedPutCount` is greater than 0, retry the request, resending only those records that might have failed
     * processing. This minimizes the possible duplicate records and also reduces the total bytes sent (and corresponding
     * charges). We recommend that you handle any duplicates at the destination.
     *
     * If PutRecordBatch throws `ServiceUnavailableException`, the API is automatically reinvoked (retried) 3 times. If the
     * exception persists, it is possible that the throughput limits have been exceeded for the Firehose stream.
     *
     * Re-invoking the Put API operations (for example, PutRecord and PutRecordBatch) can result in data duplicates. For
     * larger data assets, allow for a longer time out before retrying Put API operations.
     *
     * Data records sent to Firehose are stored for 24 hours from the time they are added to a Firehose stream as it
     * attempts to send the records to the destination. If the destination is unreachable for more than 24 hours, the data
     * is no longer available.
     *
     * ! Don't concatenate two or more base64 strings to form the data fields of your records. Instead, concatenate the raw
     * ! data, then perform base64 encoding.
     *
     * [^1]: https://docs.aws.amazon.com/firehose/latest/dev/limits.html
     *
     * @see https://docs.aws.amazon.com/firehose/latest/APIReference/API_PutRecordBatch.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-firehose-2015-08-04.html#putrecordbatch
     *
     * @param array{
     *   DeliveryStreamName: string,
     *   Records: array<Record|array>,
     *   '@region'?: string|null,
     * }|PutRecordBatchInput $input
     *
     * @throws InvalidArgumentException
     * @throws InvalidKMSResourceException
     * @throws InvalidSourceException
     * @throws ResourceNotFoundException
     * @throws ServiceUnavailableException
     */
    public function putRecordBatch($input): PutRecordBatchOutput
    {
        $input = PutRecordBatchInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutRecordBatch', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidArgumentException' => InvalidArgumentException::class,
            'InvalidKMSResourceException' => InvalidKMSResourceException::class,
            'InvalidSourceException' => InvalidSourceException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
        ]]));

        return new PutRecordBatchOutput($response);
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
                    'endpoint' => "https://firehose.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'firehose',
                    'signVersions' => ['v4'],
                ];
            case 'eusc-de-east-1':
                return [
                    'endpoint' => 'https://firehose.eusc-de-east-1.amazonaws.eu',
                    'signRegion' => 'eusc-de-east-1',
                    'signService' => 'firehose',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://firehose-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'firehose',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://firehose-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'firehose',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://firehose-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'firehose',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://firehose-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'firehose',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-east-1':
                return [
                    'endpoint' => 'https://firehose-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'firehose',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://firehose-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'firehose',
                    'signVersions' => ['v4'],
                ];
            case 'eu-isoe-west-1':
                return [
                    'endpoint' => 'https://firehose.eu-isoe-west-1.cloud.adc-e.uk',
                    'signRegion' => 'eu-isoe-west-1',
                    'signService' => 'firehose',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => "https://firehose.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'firehose',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => 'https://firehose.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'firehose',
                    'signVersions' => ['v4'],
                ];
            case 'us-isof-east-1':
            case 'us-isof-south-1':
                return [
                    'endpoint' => "https://firehose.$region.csp.hci.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'firehose',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://firehose.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'firehose',
            'signVersions' => ['v4'],
        ];
    }
}
