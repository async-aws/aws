<?php

namespace AsyncAws\MediaConvert;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\MediaConvert\Enum\BillingTagsSource;
use AsyncAws\MediaConvert\Enum\DescribeEndpointsMode;
use AsyncAws\MediaConvert\Enum\JobStatus;
use AsyncAws\MediaConvert\Enum\Order;
use AsyncAws\MediaConvert\Enum\SimulateReservedQueue;
use AsyncAws\MediaConvert\Enum\StatusUpdateInterval;
use AsyncAws\MediaConvert\Exception\BadRequestException;
use AsyncAws\MediaConvert\Exception\ConflictException;
use AsyncAws\MediaConvert\Exception\ForbiddenException;
use AsyncAws\MediaConvert\Exception\InternalServerErrorException;
use AsyncAws\MediaConvert\Exception\NotFoundException;
use AsyncAws\MediaConvert\Exception\ServiceQuotaExceededException;
use AsyncAws\MediaConvert\Exception\TooManyRequestsException;
use AsyncAws\MediaConvert\Input\CancelJobRequest;
use AsyncAws\MediaConvert\Input\CreateJobRequest;
use AsyncAws\MediaConvert\Input\DescribeEndpointsRequest;
use AsyncAws\MediaConvert\Input\GetJobRequest;
use AsyncAws\MediaConvert\Input\ListJobsRequest;
use AsyncAws\MediaConvert\Result\CancelJobResponse;
use AsyncAws\MediaConvert\Result\CreateJobResponse;
use AsyncAws\MediaConvert\Result\DescribeEndpointsResponse;
use AsyncAws\MediaConvert\Result\GetJobResponse;
use AsyncAws\MediaConvert\Result\ListJobsResponse;
use AsyncAws\MediaConvert\ValueObject\AccelerationSettings;
use AsyncAws\MediaConvert\ValueObject\HopDestination;
use AsyncAws\MediaConvert\ValueObject\JobSettings;

class MediaConvertClient extends AbstractApi
{
    /**
     * Permanently cancel a job. Once you have canceled a job, you can't start it again.
     *
     * @see https://docs.aws.amazon.com/mediaconvert/latest/apireference/API_CancelJob.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-mediaconvert-2017-08-29.html#canceljob
     *
     * @param array{
     *   Id: string,
     *   '@region'?: string|null,
     * }|CancelJobRequest $input
     *
     * @throws BadRequestException
     * @throws ConflictException
     * @throws ForbiddenException
     * @throws InternalServerErrorException
     * @throws NotFoundException
     * @throws ServiceQuotaExceededException
     * @throws TooManyRequestsException
     */
    public function cancelJob($input): CancelJobResponse
    {
        $input = CancelJobRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CancelJob', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'ConflictException' => ConflictException::class,
            'ForbiddenException' => ForbiddenException::class,
            'InternalServerErrorException' => InternalServerErrorException::class,
            'NotFoundException' => NotFoundException::class,
            'ServiceQuotaExceededException' => ServiceQuotaExceededException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new CancelJobResponse($response);
    }

    /**
     * Create a new transcoding job. For information about jobs and job settings, see the User Guide at
     * http://docs.aws.amazon.com/mediaconvert/latest/ug/what-is.html.
     *
     * @see https://docs.aws.amazon.com/mediaconvert/latest/apireference/API_CreateJob.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-mediaconvert-2017-08-29.html#createjob
     *
     * @param array{
     *   AccelerationSettings?: AccelerationSettings|array|null,
     *   BillingTagsSource?: BillingTagsSource::*|null,
     *   ClientRequestToken?: string|null,
     *   HopDestinations?: array<HopDestination|array>|null,
     *   JobEngineVersion?: string|null,
     *   JobTemplate?: string|null,
     *   Priority?: int|null,
     *   Queue?: string|null,
     *   Role: string,
     *   Settings: JobSettings|array,
     *   SimulateReservedQueue?: SimulateReservedQueue::*|null,
     *   StatusUpdateInterval?: StatusUpdateInterval::*|null,
     *   Tags?: array<string, string>|null,
     *   UserMetadata?: array<string, string>|null,
     *   '@region'?: string|null,
     * }|CreateJobRequest $input
     *
     * @throws BadRequestException
     * @throws ConflictException
     * @throws ForbiddenException
     * @throws InternalServerErrorException
     * @throws NotFoundException
     * @throws ServiceQuotaExceededException
     * @throws TooManyRequestsException
     */
    public function createJob($input): CreateJobResponse
    {
        $input = CreateJobRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateJob', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'ConflictException' => ConflictException::class,
            'ForbiddenException' => ForbiddenException::class,
            'InternalServerErrorException' => InternalServerErrorException::class,
            'NotFoundException' => NotFoundException::class,
            'ServiceQuotaExceededException' => ServiceQuotaExceededException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new CreateJobResponse($response);
    }

    /**
     * Send a request with an empty body to the regional API endpoint to get your account API endpoint. Note that
     * DescribeEndpoints is no longer required. We recommend that you send your requests directly to the regional endpoint
     * instead.
     *
     * @see https://docs.aws.amazon.com/mediaconvert/latest/apireference/API_DescribeEndpoints.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-mediaconvert-2017-08-29.html#describeendpoints
     *
     * @param array{
     *   MaxResults?: int|null,
     *   Mode?: DescribeEndpointsMode::*|null,
     *   NextToken?: string|null,
     *   '@region'?: string|null,
     * }|DescribeEndpointsRequest $input
     *
     * @throws BadRequestException
     * @throws ConflictException
     * @throws ForbiddenException
     * @throws InternalServerErrorException
     * @throws NotFoundException
     * @throws ServiceQuotaExceededException
     * @throws TooManyRequestsException
     *
     * @deprecated
     */
    public function describeEndpoints($input = []): DescribeEndpointsResponse
    {
        @trigger_error(\sprintf('The operation "%s" is deprecated by AWS.', __FUNCTION__), \E_USER_DEPRECATED);
        $input = DescribeEndpointsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeEndpoints', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'ConflictException' => ConflictException::class,
            'ForbiddenException' => ForbiddenException::class,
            'InternalServerErrorException' => InternalServerErrorException::class,
            'NotFoundException' => NotFoundException::class,
            'ServiceQuotaExceededException' => ServiceQuotaExceededException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new DescribeEndpointsResponse($response, $this, $input);
    }

    /**
     * Retrieve the JSON for a specific transcoding job.
     *
     * @see https://docs.aws.amazon.com/mediaconvert/latest/apireference/API_GetJob.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-mediaconvert-2017-08-29.html#getjob
     *
     * @param array{
     *   Id: string,
     *   '@region'?: string|null,
     * }|GetJobRequest $input
     *
     * @throws BadRequestException
     * @throws ConflictException
     * @throws ForbiddenException
     * @throws InternalServerErrorException
     * @throws NotFoundException
     * @throws ServiceQuotaExceededException
     * @throws TooManyRequestsException
     */
    public function getJob($input): GetJobResponse
    {
        $input = GetJobRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetJob', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'ConflictException' => ConflictException::class,
            'ForbiddenException' => ForbiddenException::class,
            'InternalServerErrorException' => InternalServerErrorException::class,
            'NotFoundException' => NotFoundException::class,
            'ServiceQuotaExceededException' => ServiceQuotaExceededException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new GetJobResponse($response);
    }

    /**
     * Retrieve a JSON array of up to twenty of your most recently created jobs. This array includes in-process, completed,
     * and errored jobs. This will return the jobs themselves, not just a list of the jobs. To retrieve the twenty next most
     * recent jobs, use the nextToken string returned with the array.
     *
     * @see https://docs.aws.amazon.com/mediaconvert/latest/apireference/API_ListJobs.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-mediaconvert-2017-08-29.html#listjobs
     *
     * @param array{
     *   MaxResults?: int|null,
     *   NextToken?: string|null,
     *   Order?: Order::*|null,
     *   Queue?: string|null,
     *   Status?: JobStatus::*|null,
     *   '@region'?: string|null,
     * }|ListJobsRequest $input
     *
     * @throws BadRequestException
     * @throws ConflictException
     * @throws ForbiddenException
     * @throws InternalServerErrorException
     * @throws NotFoundException
     * @throws ServiceQuotaExceededException
     * @throws TooManyRequestsException
     */
    public function listJobs($input = []): ListJobsResponse
    {
        $input = ListJobsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListJobs', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'ConflictException' => ConflictException::class,
            'ForbiddenException' => ForbiddenException::class,
            'InternalServerErrorException' => InternalServerErrorException::class,
            'NotFoundException' => NotFoundException::class,
            'ServiceQuotaExceededException' => ServiceQuotaExceededException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new ListJobsResponse($response, $this, $input);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new JsonRestAwsErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        switch ($region) {
            case 'cn-northwest-1':
                return [
                    'endpoint' => 'https://mediaconvert.cn-northwest-1.amazonaws.com.cn',
                    'signRegion' => 'cn-northwest-1',
                    'signService' => 'mediaconvert',
                    'signVersions' => ['v4'],
                ];
            case 'fips-ca-central-1':
                return [
                    'endpoint' => 'https://mediaconvert-fips.ca-central-1.amazonaws.com',
                    'signRegion' => 'ca-central-1',
                    'signService' => 'mediaconvert',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://mediaconvert-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'mediaconvert',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://mediaconvert-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'mediaconvert',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://mediaconvert-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'mediaconvert',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://mediaconvert-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'mediaconvert',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://mediaconvert.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'mediaconvert',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://mediaconvert.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'mediaconvert',
            'signVersions' => ['v4'],
        ];
    }
}
