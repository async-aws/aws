<?php

namespace AsyncAws\Translate\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The confidence that Amazon Comprehend accurately detected the source language is low. If a low confidence level is
 * acceptable for your application, you can use the language in the exception to call Amazon Translate again. For more
 * information, see the DetectDominantLanguage [^1] operation in the *Amazon Comprehend Developer Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/comprehend/latest/dg/API_DetectDominantLanguage.html
 */
final class DetectedLanguageLowConfidenceException extends ClientException
{
    /**
     * The language code of the auto-detected language from Amazon Comprehend.
     *
     * @var string|null
     */
    private $detectedLanguageCode;

    public function getDetectedLanguageCode(): ?string
    {
        return $this->detectedLanguageCode;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->detectedLanguageCode = isset($data['DetectedLanguageCode']) ? (string) $data['DetectedLanguageCode'] : null;
    }
}
