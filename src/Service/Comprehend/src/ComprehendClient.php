<?php

namespace AsyncAws\Comprehend;

use AsyncAws\Comprehend\Exception\InternalServerException;
use AsyncAws\Comprehend\Exception\InvalidRequestException;
use AsyncAws\Comprehend\Exception\TextSizeLimitExceededException;
use AsyncAws\Comprehend\Input\DetectDominantLanguageRequest;
use AsyncAws\Comprehend\Result\DetectDominantLanguageResponse;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\UnsupportedRegion;
use AsyncAws\Core\RequestContext;

class ComprehendClient extends AbstractApi
{
    /**
     * Determines the dominant language of the input text. For a list of languages that Amazon Comprehend can detect, see
     * Amazon Comprehend Supported Languages [^1].
     *
     * [^1]: https://docs.aws.amazon.com/comprehend/latest/dg/how-languages.html
     *
     * @see https://docs.aws.amazon.com/comprehend/latest/dg/API_DetectDominantLanguage.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-comprehend-2017-11-27.html#detectdominantlanguage
     *
     * @param array{
     *   Text: string,
     *   '@region'?: string|null,
     * }|DetectDominantLanguageRequest $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     * @throws TextSizeLimitExceededException
     */
    public function detectDominantLanguage($input): DetectDominantLanguageResponse
    {
        $input = DetectDominantLanguageRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DetectDominantLanguage', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'TextSizeLimitExceededException' => TextSizeLimitExceededException::class,
        ]]));

        return new DetectDominantLanguageResponse($response);
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
            case 'ap-northeast-1':
            case 'ap-northeast-2':
            case 'ap-south-1':
            case 'ap-southeast-1':
            case 'ap-southeast-2':
            case 'ca-central-1':
            case 'eu-central-1':
            case 'eu-west-1':
            case 'eu-west-2':
            case 'us-east-1':
            case 'us-east-2':
            case 'us-gov-west-1':
            case 'us-west-2':
                return [
                    'endpoint' => "https://comprehend.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'comprehend',
                    'signVersions' => ['v4'],
                ];
            case 'fips-ca-central-1':
                return [
                    'endpoint' => 'https://comprehend-fips.ca-central-1.amazonaws.com',
                    'signRegion' => 'ca-central-1',
                    'signService' => 'comprehend',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://comprehend-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'comprehend',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://comprehend-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'comprehend',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://comprehend-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'comprehend',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://comprehend-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'comprehend',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
                return [
                    'endpoint' => 'https://comprehend.us-iso-east-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-east-1',
                    'signService' => 'comprehend',
                    'signVersions' => ['v4'],
                ];
            case 'us-isof-east-1':
            case 'us-isof-south-1':
                return [
                    'endpoint' => "https://comprehend.$region.csp.hci.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'comprehend',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-iso-east-1':
                return [
                    'endpoint' => 'https://comprehend-fips.us-iso-east-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-east-1',
                    'signService' => 'comprehend',
                    'signVersions' => ['v4'],
                ];
        }

        throw new UnsupportedRegion(\sprintf('The region "%s" is not supported by "Comprehend".', $region));
    }
}
