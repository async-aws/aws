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
     * Because the Timestream SDKs are designed to transparently work with the service’s architecture, including the
     * management and mapping of the service endpoints, *we don't recommend that you use this API operation unless*:
     *
     * - You are using VPC endpoints (Amazon Web Services PrivateLink) with Timestream [^1]
     * - Your application uses a programming language that does not yet have SDK support
     * - You require better control over the client-side implementation
     *
     * For detailed information on how and when to use and implement DescribeEndpoints, see The Endpoint Discovery Pattern
     * [^2].
     *
     * [^1]: https://docs.aws.amazon.com/timestream/latest/developerguide/VPCEndpoints
     * [^2]: https://docs.aws.amazon.com/timestream/latest/developerguide/Using.API.html#Using-API.endpoint-discovery
     *
     * @see https://docs.aws.amazon.com/timestream/latest/developerguide/API_DescribeEndpoints.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ingest.timestream-2018-11-01.html#describeendpoints
     *
     * @param array{
     *   '@region'?: string|null,
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
     * Timestream supports eventual consistency read semantics. This means that when you query data immediately after
     * writing a batch of data into Timestream, the query results might not reflect the results of a recently completed
     * write operation. The results may also include some stale data. If you repeat the query request after a short time,
     * the results should return the latest data. Service quotas apply [^1].
     *
     * See code sample [^2] for details.
     *
     * **Upserts**
     *
     * You can use the `Version` parameter in a `WriteRecords` request to update data points. Timestream tracks a version
     * number with each record. `Version` defaults to `1` when it's not specified for the record in the request. Timestream
     * updates an existing record’s measure value along with its `Version` when it receives a write request with a higher
     * `Version` number for that record. When it receives an update request where the measure value is the same as that of
     * the existing record, Timestream still updates `Version`, if it is greater than the existing value of `Version`. You
     * can update a data point as many times as desired, as long as the value of `Version` continuously increases.
     *
     * For example, suppose you write a new record without indicating `Version` in the request. Timestream stores this
     * record, and set `Version` to `1`. Now, suppose you try to update this record with a `WriteRecords` request of the
     * same record with a different measure value but, like before, do not provide `Version`. In this case, Timestream will
     * reject this update with a `RejectedRecordsException` since the updated record’s version is not greater than the
     * existing value of Version.
     *
     * However, if you were to resend the update request with `Version` set to `2`, Timestream would then succeed in
     * updating the record’s value, and the `Version` would be set to `2`. Next, suppose you sent a `WriteRecords` request
     * with this same record and an identical measure value, but with `Version` set to `3`. In this case, Timestream would
     * only update `Version` to `3`. Any further updates would need to send a version number greater than `3`, or the update
     * requests would receive a `RejectedRecordsException`.
     *
     * [^1]: https://docs.aws.amazon.com/timestream/latest/developerguide/ts-limits.html
     * [^2]: https://docs.aws.amazon.com/timestream/latest/developerguide/code-samples.write.html
     *
     * @see https://docs.aws.amazon.com/timestream/latest/developerguide/API_WriteRecords.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-ingest.timestream-2018-11-01.html#writerecords
     *
     * @param array{
     *   DatabaseName: string,
     *   TableName: string,
     *   CommonAttributes?: null|Record|array,
     *   Records: array<Record|array>,
     *   '@region'?: string|null,
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
