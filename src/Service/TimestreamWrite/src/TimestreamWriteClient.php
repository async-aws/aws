<?php

namespace AsyncAws\TimestreamWrite;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\TimestreamWrite\Exception\AccessDeniedException;
use AsyncAws\TimestreamWrite\Exception\InternalServerException;
use AsyncAws\TimestreamWrite\Exception\InvalidEndpointException;
use AsyncAws\TimestreamWrite\Exception\RejectedRecordsException;
use AsyncAws\TimestreamWrite\Exception\ResourceNotFoundException;
use AsyncAws\TimestreamWrite\Exception\ThrottlingException;
use AsyncAws\TimestreamWrite\Exception\ValidationException;
use AsyncAws\TimestreamWrite\Input\WriteRecordsRequest;
use AsyncAws\TimestreamWrite\Result\WriteRecordsResponse;
use AsyncAws\TimestreamWrite\ValueObject\Record;

class TimestreamWriteClient extends AbstractApi
{
    /**
     * The WriteRecords operation enables you to write your time series data into Timestream. You can specify a single data
     * point or a batch of data points to be inserted into the system. Timestream offers you with a flexible schema that
     * auto detects the column names and data types for your Timestream tables based on the dimension names and data types
     * of the data points you specify when invoking writes into the database. Timestream support eventual consistency read
     * semantics. This means that when you query data immediately after writing a batch of data into Timestream, the query
     * results might not reflect the results of a recently completed write operation. The results may also include some
     * stale data. If you repeat the query request after a short time, the results should return the latest data. Service
     * quotas apply.
     *
     * @see https://docs.aws.amazon.com/timestream/latest/developerguide/ts-limits.html
     * @see https://docs.aws.amazon.com/timestream/latest/developerguide/API_WriteRecords.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ingest.timestream-2018-11-01.html#writerecords
     *
     * @param array{
     *   DatabaseName: string,
     *   TableName: string,
     *   CommonAttributes?: Record|array,
     *   Records: Record[],
     *   @region?: string,
     * }|WriteRecordsRequest $input
     *
     * @throws InternalServerException
     * @throws ThrottlingException
     * @throws ValidationException
     * @throws ResourceNotFoundException
     * @throws AccessDeniedException
     * @throws RejectedRecordsException
     * @throws InvalidEndpointException
     */
    public function writeRecords($input): WriteRecordsResponse
    {
        $input = WriteRecordsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'WriteRecords', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'AccessDeniedException' => AccessDeniedException::class,
            'RejectedRecordsException' => RejectedRecordsException::class,
            'InvalidEndpointException' => InvalidEndpointException::class,
        ]]));

        return new WriteRecordsResponse($response);
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

        return [
            'endpoint' => "https://ingest.timestream.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'timestream',
            'signVersions' => ['v4'],
        ];
    }
}
