<?php

namespace AsyncAws\Translate;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
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
     * codes, see Supported languages.
     *
     * @see https://docs.aws.amazon.com/translate/latest/dg/what-is-languages.html
     * @see https://docs.aws.amazon.com/translate/latest/dg/API_TranslateText.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-translate-2017-07-01.html#translatetext
     *
     * @param array{
     *   Text: string,
     *   TerminologyNames?: string[],
     *   SourceLanguageCode: string,
     *   TargetLanguageCode: string,
     *   Settings?: TranslationSettings|array,
     *
     *   @region?: string,
     * }|TranslateTextRequest $input
     *
     * @throws InvalidRequestException
     * @throws TextSizeLimitExceededException
     * @throws TooManyRequestsException
     * @throws UnsupportedLanguagePairException
     * @throws DetectedLanguageLowConfidenceException
     * @throws ResourceNotFoundException
     * @throws InternalServerException
     * @throws ServiceUnavailableException
     */
    public function translateText($input): TranslateTextResponse
    {
        $input = TranslateTextRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'TranslateText', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidRequestException' => InvalidRequestException::class,
            'TextSizeLimitExceededException' => TextSizeLimitExceededException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UnsupportedLanguagePairException' => UnsupportedLanguagePairException::class,
            'DetectedLanguageLowConfidenceException' => DetectedLanguageLowConfidenceException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InternalServerException' => InternalServerException::class,
            'ServiceUnavailableException' => ServiceUnavailableException::class,
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
            case 'us-iso-east-1':
                return [
                    'endpoint' => 'https://translate.us-iso-east-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-east-1',
                    'signService' => 'translate',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://translate.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'translate',
            'signVersions' => ['v4'],
        ];
    }
}
