<?php

namespace AsyncAws\XRay;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\XRay\Exception\InvalidRequestException;
use AsyncAws\XRay\Exception\ThrottledException;
use AsyncAws\XRay\Input\PutTraceSegmentsRequest;
use AsyncAws\XRay\Result\PutTraceSegmentsResult;

class XRayClient extends AbstractApi
{
    /**
     * Uploads segment documents to Amazon Web Services X-Ray. The X-Ray SDK generates segment documents and sends them to
     * the X-Ray daemon, which uploads them in batches. A segment document can be a completed segment, an in-progress
     * segment, or an array of subsegments.
     *
     * @see https://docs.aws.amazon.com/xray/index.html
     * @see https://docs.aws.amazon.com/xray/latest/api/API_PutTraceSegments.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-xray-2016-04-12.html#puttracesegments
     *
     * @param array{
     *   TraceSegmentDocuments: string[],
     *   @region?: string,
     * }|PutTraceSegmentsRequest $input
     *
     * @throws InvalidRequestException
     * @throws ThrottledException
     */
    public function putTraceSegments($input): PutTraceSegmentsResult
    {
        $input = PutTraceSegmentsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutTraceSegments', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidRequestException' => InvalidRequestException::class,
            'ThrottledException' => ThrottledException::class,
        ]]));

        return new PutTraceSegmentsResult($response);
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
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://xray.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'xray',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://xray-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'xray',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://xray-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'xray',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-east-1':
                return [
                    'endpoint' => 'https://xray-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'xray',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://xray-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'xray',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://xray-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'xray',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://xray-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'xray',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://xray.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'xray',
            'signVersions' => ['v4'],
        ];
    }
}
