<?php

namespace AsyncAws\Translate;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\UnsupportedRegion;
use AsyncAws\Core\RequestContext;
use AsyncAws\Translate\Exception\DetectedLanguageLowConfidenceException;
use AsyncAws\Translate\Exception\InternalServerException;
use AsyncAws\Translate\Exception\InvalidRequestException;
use AsyncAws\Translate\Exception\ResourceNotFoundException;
use AsyncAws\Translate\Exception\ServiceUnavailableException;
use AsyncAws\Translate\Exception\TextSizeLimitExceededException;
use AsyncAws\Translate\Exception\TooManyRequestsException;
use AsyncAws\Translate\Exception\UnsupportedLanguagePairException;
use AsyncAws\Translate\Input\TranslateTextRequest;
use AsyncAws\Translate\Result\TranslateTextResponse;
use AsyncAws\Translate\ValueObject\TranslationSettings;

class TranslateClient extends AbstractApi
{
    /**
     * Translates input text from the source language to the target language. For a list of available languages and language
     * codes, see Supported languages [^1].
     *
     * [^1]: https://docs.aws.amazon.com/translate/latest/dg/what-is-languages.html
     *
     * @see https://docs.aws.amazon.com/translate/latest/dg/API_TranslateText.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-translate-2017-07-01.html#translatetext
     *
     * @param array{
     *   Text: string,
     *   TerminologyNames?: string[]|null,
     *   SourceLanguageCode: string,
     *   TargetLanguageCode: string,
     *   Settings?: TranslationSettings|array|null,
     *   '@region'?: string|null,
     * }|TranslateTextRequest $input
     *
     * @throws DetectedLanguageLowConfidenceException
     * @throws InternalServerException
     * @throws InvalidRequestException
     * @throws ResourceNotFoundException
     * @throws ServiceUnavailableException
     * @throws TextSizeLimitExceededException
     * @throws TooManyRequestsException
     * @throws UnsupportedLanguagePairException
     */
    public function translateText($input): TranslateTextResponse
    {
        $input = TranslateTextRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'TranslateText', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'DetectedLanguageLowConfidenceException' => DetectedLanguageLowConfidenceException::class,
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
            'TextSizeLimitExceededException' => TextSizeLimitExceededException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UnsupportedLanguagePairException' => UnsupportedLanguagePairException::class,
        ]]));

        return new TranslateTextResponse($response);
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
            case 'ap-east-1':
            case 'ap-northeast-1':
            case 'ap-northeast-2':
            case 'ap-south-1':
            case 'ap-southeast-1':
            case 'ap-southeast-2':
            case 'ca-central-1':
            case 'eu-central-1':
            case 'eu-north-1':
            case 'eu-west-1':
            case 'eu-west-2':
            case 'eu-west-3':
            case 'us-east-1':
            case 'us-east-2':
            case 'us-gov-west-1':
            case 'us-west-1':
            case 'us-west-2':
                return [
                    'endpoint' => "https://translate.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'translate',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-1-fips':
                return [
                    'endpoint' => 'https://translate-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'translate',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-2-fips':
                return [
                    'endpoint' => 'https://translate-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'translate',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-1-fips':
                return [
                    'endpoint' => 'https://translate-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'translate',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-2-fips':
                return [
                    'endpoint' => 'https://translate-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'translate',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-west-1-fips':
                return [
                    'endpoint' => 'https://translate-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'translate',
                    'signVersions' => ['v4'],
                ];
            case 'us-isof-east-1':
            case 'us-isof-south-1':
                return [
                    'endpoint' => "https://translate.$region.csp.hci.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'translate',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
                return [
                    'endpoint' => 'https://translate.us-iso-east-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-east-1',
                    'signService' => 'translate',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1-fips':
                return [
                    'endpoint' => 'https://translate-fips.us-iso-east-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-east-1',
                    'signService' => 'translate',
                    'signVersions' => ['v4'],
                ];
        }

        throw new UnsupportedRegion(\sprintf('The region "%s" is not supported by "Translate".', $region));
    }
}
