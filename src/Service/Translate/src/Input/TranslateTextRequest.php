<?php

namespace AsyncAws\Translate\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Translate\ValueObject\TranslationSettings;

final class TranslateTextRequest extends Input
{
    /**
     * The text to translate. The text string can be a maximum of 10,000 bytes long. Depending on your character set, this
     * may be fewer than 10,000 characters.
     *
     * @required
     *
     * @var string|null
     */
    private $text;

    /**
     * The name of a terminology list file to add to the translation job. This file provides source terms and the desired
     * translation for each term. A terminology list can contain a maximum of 256 terms. You can use one custom terminology
     * resource in your translation request.
     *
     * Use the ListTerminologies operation to get the available terminology lists.
     *
     * For more information about custom terminology lists, see Custom terminology [^1].
     *
     * [^1]: https://docs.aws.amazon.com/translate/latest/dg/how-custom-terminology.html
     *
     * @var string[]|null
     */
    private $terminologyNames;

    /**
     * The language code for the language of the source text. For a list of language codes, see Supported languages [^1].
     *
     * To have Amazon Translate determine the source language of your text, you can specify `auto` in the
     * `SourceLanguageCode` field. If you specify `auto`, Amazon Translate will call Amazon Comprehend [^2] to determine the
     * source language.
     *
     * > If you specify `auto`, you must send the `TranslateText` request in a region that supports Amazon Comprehend.
     * > Otherwise, the request returns an error indicating that autodetect is not supported.
     *
     * [^1]: https://docs.aws.amazon.com/translate/latest/dg/what-is-languages.html
     * [^2]: https://docs.aws.amazon.com/comprehend/latest/dg/comprehend-general.html
     *
     * @required
     *
     * @var string|null
     */
    private $sourceLanguageCode;

    /**
     * The language code requested for the language of the target text. For a list of language codes, see Supported
     * languages [^1].
     *
     * [^1]: https://docs.aws.amazon.com/translate/latest/dg/what-is-languages.html
     *
     * @required
     *
     * @var string|null
     */
    private $targetLanguageCode;

    /**
     * Settings to configure your translation output. You can configure the following options:
     *
     * - Brevity: reduces the length of the translated output for most translations.
     * - Formality: sets the formality level of the output text.
     * - Profanity: masks profane words and phrases in your translation output.
     *
     * @var TranslationSettings|null
     */
    private $settings;

    /**
     * @param array{
     *   Text?: string,
     *   TerminologyNames?: string[]|null,
     *   SourceLanguageCode?: string,
     *   TargetLanguageCode?: string,
     *   Settings?: TranslationSettings|array|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->text = $input['Text'] ?? null;
        $this->terminologyNames = $input['TerminologyNames'] ?? null;
        $this->sourceLanguageCode = $input['SourceLanguageCode'] ?? null;
        $this->targetLanguageCode = $input['TargetLanguageCode'] ?? null;
        $this->settings = isset($input['Settings']) ? TranslationSettings::create($input['Settings']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Text?: string,
     *   TerminologyNames?: string[]|null,
     *   SourceLanguageCode?: string,
     *   TargetLanguageCode?: string,
     *   Settings?: TranslationSettings|array|null,
     *   '@region'?: string|null,
     * }|TranslateTextRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getSettings(): ?TranslationSettings
    {
        return $this->settings;
    }

    public function getSourceLanguageCode(): ?string
    {
        return $this->sourceLanguageCode;
    }

    public function getTargetLanguageCode(): ?string
    {
        return $this->targetLanguageCode;
    }

    /**
     * @return string[]
     */
    public function getTerminologyNames(): array
    {
        return $this->terminologyNames ?? [];
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AWSShineFrontendService_20170701.TranslateText',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setSettings(?TranslationSettings $value): self
    {
        $this->settings = $value;

        return $this;
    }

    public function setSourceLanguageCode(?string $value): self
    {
        $this->sourceLanguageCode = $value;

        return $this;
    }

    public function setTargetLanguageCode(?string $value): self
    {
        $this->targetLanguageCode = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setTerminologyNames(array $value): self
    {
        $this->terminologyNames = $value;

        return $this;
    }

    public function setText(?string $value): self
    {
        $this->text = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->text) {
            throw new InvalidArgument(\sprintf('Missing parameter "Text" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Text'] = $v;
        if (null !== $v = $this->terminologyNames) {
            $index = -1;
            $payload['TerminologyNames'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['TerminologyNames'][$index] = $listValue;
            }
        }
        if (null === $v = $this->sourceLanguageCode) {
            throw new InvalidArgument(\sprintf('Missing parameter "SourceLanguageCode" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['SourceLanguageCode'] = $v;
        if (null === $v = $this->targetLanguageCode) {
            throw new InvalidArgument(\sprintf('Missing parameter "TargetLanguageCode" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TargetLanguageCode'] = $v;
        if (null !== $v = $this->settings) {
            $payload['Settings'] = $v->requestBody();
        }

        return $payload;
    }
}
