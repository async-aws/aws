<?php

namespace AsyncAws\Translate\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Amazon Translate does not support translation from the language of the source text into the requested target
 * language. For more information, see Supported languages [^1].
 *
 * [^1]: https://docs.aws.amazon.com/translate/latest/dg/what-is-languages.html
 */
final class UnsupportedLanguagePairException extends ClientException
{
    /**
     * The language code for the language of the input text.
     *
     * @var string|null
     */
    private $sourceLanguageCode;

    /**
     * The language code for the language of the translated text.
     *
     * @var string|null
     */
    private $targetLanguageCode;

    public function getSourceLanguageCode(): ?string
    {
        return $this->sourceLanguageCode;
    }

    public function getTargetLanguageCode(): ?string
    {
        return $this->targetLanguageCode;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->sourceLanguageCode = isset($data['SourceLanguageCode']) ? (string) $data['SourceLanguageCode'] : null;
        $this->targetLanguageCode = isset($data['TargetLanguageCode']) ? (string) $data['TargetLanguageCode'] : null;
    }
}
