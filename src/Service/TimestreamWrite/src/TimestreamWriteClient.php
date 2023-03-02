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
use AsyncAws\TimestreamWrite\Input\DescribeEndpointsRequest;
use AsyncAws\TimestreamWrite\Input\WriteRecordsRequest;
use AsyncAws\TimestreamWrite\Result\DescribeEndpointsResponse;
use AsyncAws\TimestreamWrite\Result\WriteRecordsResponse;
use AsyncAws\TimestreamWrite\ValueObject\Record;

class TimestreamWriteClient extends AbstractApi
{
    /**
     * Returns a list of available endpoints to make Timestream API calls against. This API operation is available through
     * both the Write and Query APIs.
     *
     * @see https://docs.aws.amazon.com/timestream/latest/developerguide/API_DescribeEndpoints.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ingest.timestream-2018-11-01.html#describeendpoints
     *
     * @param array{
     *
     *   @region?: string,
     * }|DescribeEndpointsRequest $input
     *
     * @throws InternalServerException
     * @throws ValidationException
     * @throws ThrottlingException
     */
    public function describeEndpoints($input = []): DescribeEndpointsResponse
    {
        $input = DescribeEndpointsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeEndpoints', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'ValidationException' => ValidationException::class,
            'ThrottlingException' => ThrottlingException::class,
        ]]));

        return new DescribeEndpointsResponse($response);
    }

    /**
     * Enables you to write your time-series data into Timestream. You can specify a single data point or a batch of data
     * points to be inserted into the system. Timestream offers you a flexible schema that auto detects the column names and
     * data types for your Timestream tables based on the dimension names and data types of the data points you specify when
     * invoking writes into the database.
     *
     * @see https://docs.aws.amazon.com/timestream/latest/developerguide/API_WriteRecords.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ingest.timestream-2018-11-01.html#writerecords
     *
     * @param array{
     *   DatabaseName: string,
     *   TableName: string,
     *   CommonAttributes?: Record|array,
     *   Records: Record[],
     *
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
        ], 'requiresEndpointDiscovery' => true, 'usesEndpointDiscovery' => true]));

        return new WriteRecordsResponse($response);
    }

    protected function discoverEndpoints(?string $region): array
    {
        return $this->describeEndpoints($region ? ['@region' => $region] : [])->getEndpoints();
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
            case 'ingest-us-east-1':
                return [
                    'endpoint' => 'https://ingest.timestream.ingest-us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'timestream',
                    'signVersions' => ['v4'],
                ];
            case 'ingest-us-east-2':
                return [
                    'endpoint' => 'https://ingest.timestream.ingest-us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'timestream',
                    'signVersions' => ['v4'],
                ];
            case 'ingest-us-west-2':
                return [
                    'endpoint' => 'https://ingest.timestream.ingest-us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'timestream',
                    'signVersions' => ['v4'],
                ];
            case 'ingest-fips-us-east-1':
                return [
                    'endpoint' => 'https://ingest.timestream-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'timestream',
                    'signVersions' => ['v4'],
                ];
            case 'ingest-fips-us-east-2':
                return [
                    'endpoint' => 'https://ingest.timestream-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'timestream',
                    'signVersions' => ['v4'],
                ];
            case 'ingest-fips-us-west-2':
                return [
                    'endpoint' => 'https://ingest.timestream-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'timestream',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-west-1-fips':
                return [
                    'endpoint' => 'https://ingest.timestream.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'timestream',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://ingest.timestream.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'timestream',
            'signVersions' => ['v4'],
        ];
    }
}
