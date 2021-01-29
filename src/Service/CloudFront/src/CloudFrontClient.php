<?php

namespace AsyncAws\CloudFront;

use AsyncAws\CloudFront\Exception\AccessDeniedException;
use AsyncAws\CloudFront\Exception\BatchTooLargeException;
use AsyncAws\CloudFront\Exception\InconsistentQuantitiesException;
use AsyncAws\CloudFront\Exception\InvalidArgumentException;
use AsyncAws\CloudFront\Exception\MissingBodyException;
use AsyncAws\CloudFront\Exception\NoSuchDistributionException;
use AsyncAws\CloudFront\Exception\TooManyInvalidationsInProgressException;
use AsyncAws\CloudFront\Input\CreateInvalidationRequest;
use AsyncAws\CloudFront\Result\CreateInvalidationResult;
use AsyncAws\CloudFront\ValueObject\InvalidationBatch;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\XmlAwsErrorFactory;
use AsyncAws\Core\RequestContext;

class CloudFrontClient extends AbstractApi
{
    /**
     * Create a new invalidation.
     *
     * @see https://docs.aws.amazon.com/cloudfront/latest/APIReference/API_CreateInvalidation2019_03_26.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cloudfront-2019-03-26.html#createinvalidation2019_03_26
     *
     * @param array{
     *   DistributionId: string,
     *   InvalidationBatch: InvalidationBatch|array,
     *   @region?: string,
     * }|CreateInvalidationRequest $input
     *
     * @throws AccessDeniedException
     * @throws MissingBodyException
     * @throws InvalidArgumentException
     * @throws NoSuchDistributionException
     * @throws BatchTooLargeException
     * @throws TooManyInvalidationsInProgressException
     * @throws InconsistentQuantitiesException
     */
    public function createInvalidation($input): CreateInvalidationResult
    {
        $input = CreateInvalidationRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateInvalidation2019_03_26', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDenied' => AccessDeniedException::class,
            'MissingBody' => MissingBodyException::class,
            'InvalidArgument' => InvalidArgumentException::class,
            'NoSuchDistribution' => NoSuchDistributionException::class,
            'BatchTooLarge' => BatchTooLargeException::class,
            'TooManyInvalidationsInProgress' => TooManyInvalidationsInProgressException::class,
            'InconsistentQuantities' => InconsistentQuantitiesException::class,
        ]]));

        return new CreateInvalidationResult($response);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new XmlAwsErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            return [
                'endpoint' => 'https://cloudfront.amazonaws.com',
                'signRegion' => 'us-east-1',
                'signService' => 'cloudfront',
                'signVersions' => ['v4'],
            ];
        }

        switch ($region) {
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => 'https://cloudfront.cn-northwest-1.amazonaws.com.cn',
                    'signRegion' => 'cn-northwest-1',
                    'signService' => 'cloudfront',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => 'https://cloudfront.amazonaws.com',
            'signRegion' => 'us-east-1',
            'signService' => 'cloudfront',
            'signVersions' => ['v4'],
        ];
    }
}
