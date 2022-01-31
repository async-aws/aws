<?php

namespace AsyncAws\Firehose;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\Firehose\Exception\InvalidArgumentException;
use AsyncAws\Firehose\Exception\InvalidKMSResourceException;
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
     * Writes a single data record into an Amazon Kinesis Data Firehose delivery stream. To write multiple data records into
     * a delivery stream, use PutRecordBatch. Applications using these operations are referred to as producers.
     *
     * @see https://docs.aws.amazon.com/firehose/latest/APIReference/API_PutRecord.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-firehose-2015-08-04.html#putrecord
     *
     * @param array{
     *   DeliveryStreamName: string,
     *   Record: Record|array,
     *   @region?: string,
     * }|PutRecordInput $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidArgumentException
     * @throws InvalidKMSResourceException
     * @throws ServiceUnavailableException
     */
    public function putRecord($input): PutRecordOutput
    {
        $input = PutRecordInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutRecord', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'InvalidKMSResourceException' => InvalidKMSResourceException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
        ]]));

        return new PutRecordOutput($response);
    }

    /**
     * Writes multiple data records into a delivery stream in a single call, which can achieve higher throughput per
     * producer than when writing single records. To write single data records into a delivery stream, use PutRecord.
     * Applications using these operations are referred to as producers.
     *
     * @see https://docs.aws.amazon.com/firehose/latest/APIReference/API_PutRecordBatch.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-firehose-2015-08-04.html#putrecordbatch
     *
     * @param array{
     *   DeliveryStreamName: string,
     *   Records: Record[],
     *   @region?: string,
     * }|PutRecordBatchInput $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidArgumentException
     * @throws InvalidKMSResourceException
     * @throws ServiceUnavailableException
     */
    public function putRecordBatch($input): PutRecordBatchOutput
    {
        $input = PutRecordBatchInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutRecordBatch', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'InvalidKMSResourceException' => InvalidKMSResourceException::class,
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
            case 'us-iso-east-1':
                return [
                    'endpoint' => 'https://firehose.us-iso-east-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-east-1',
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
        }

        return [
            'endpoint' => "https://firehose.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'firehose',
            'signVersions' => ['v4'],
        ];
    }
}
