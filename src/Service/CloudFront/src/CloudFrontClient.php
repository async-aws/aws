<?php

namespace AsyncAws\CloudFront;

use AsyncAws\CloudFront\Input\CreateInvalidationRequest;
use AsyncAws\CloudFront\Result\CreateInvalidationResult;
use AsyncAws\CloudFront\ValueObject\InvalidationBatch;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\Exception\UnsupportedRegion;
use AsyncAws\Core\RequestContext;

class CloudFrontClient extends AbstractApi
{
    /**
     * Create a new invalidation.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cloudfront-2019-03-26.html#createinvalidation2019_03_26
     *
     * @param array{
     *   DistributionId: string,
     *   InvalidationBatch: InvalidationBatch|array,
     *   @region?: string,
     * }|CreateInvalidationRequest $input
     */
    public function createInvalidation($input): CreateInvalidationResult
    {
        $input = CreateInvalidationRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateInvalidation2019_03_26', 'region' => $input->getRegion()]));

        return new CreateInvalidationResult($response);
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
            case 'af-south-1':
            case 'ap-east-1':
            case 'ap-northeast-1':
            case 'ap-northeast-2':
            case 'ap-south-1':
            case 'ap-southeast-1':
            case 'ap-southeast-2':
            case 'ca-central-1':
            case 'eu-central-1':
            case 'eu-north-1':
            case 'eu-south-1':
            case 'eu-west-1':
            case 'eu-west-2':
            case 'eu-west-3':
            case 'me-south-1':
            case 'sa-east-1':
            case 'us-east-1':
            case 'us-east-2':
            case 'us-west-1':
            case 'us-west-2':
                return [
                    'endpoint' => 'https://cloudfront.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'cloudfront',
                    'signVersions' => ['v4'],
                ];
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => 'https://cloudfront.cn-northwest-1.amazonaws.com.cn',
                    'signRegion' => 'cn-northwest-1',
                    'signService' => 'cloudfront',
                    'signVersions' => ['v4'],
                ];
        }

        throw new UnsupportedRegion(sprintf('The region "%s" is not supported by "CloudFront".', $region));
    }
}
