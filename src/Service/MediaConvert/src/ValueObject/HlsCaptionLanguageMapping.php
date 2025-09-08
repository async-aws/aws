<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\LanguageCode;

/**
 * Caption Language Mapping.
 */
final class HlsCaptionLanguageMapping
{
    /**
     * Caption channel.
     *
     * @var int|null
     */
    private $captionChannel;

    /**
     * Specify the language for this captions channel, using the ISO 639-2 or ISO 639-3 three-letter language code.
     *
     * @var string|null
     */
    private $customLanguageCode;

    /**
     * Specify the language, using the ISO 639-2 three-letter code listed at
     * https://www.loc.gov/standards/iso639-2/php/code_list.php.
     *
     * @var LanguageCode::*|null
     */
    private $languageCode;

    /**
     * Caption language description.
     *
     * @var string|null
     */
    private $languageDescription;

    /**
     * @param array{
     *   CaptionChannel?: int|null,
     *   CustomLanguageCode?: string|null,
     *   LanguageCode?: LanguageCode::*|null,
     *   LanguageDescription?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->captionChannel = $input['CaptionChannel'] ?? null;
        $this->customLanguageCode = $input['CustomLanguageCode'] ?? null;
        $this->languageCode = $input['LanguageCode'] ?? null;
        $this->languageDescription = $input['LanguageDescription'] ?? null;
    }

    /**
     * @param array{
     *   CaptionChannel?: int|null,
     *   CustomLanguageCode?: string|null,
     *   LanguageCode?: LanguageCode::*|null,
     *   LanguageDescription?: string|null,
     * }|HlsCaptionLanguageMapping $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCaptionChannel(): ?int
    {
        return $this->captionChannel;
    }

    public function getCustomLanguageCode(): ?string
    {
        return $this->customLanguageCode;
    }

    /**
     * @return LanguageCode::*|null
     */
    public function getLanguageCode(): ?string
    {
        return $this->languageCode;
    }

    public function getLanguageDescription(): ?string
    {
        return $this->languageDescription;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->captionChannel) {
            $payload['captionChannel'] = $v;
        }
        if (null !== $v = $this->customLanguageCode) {
            $payload['customLanguageCode'] = $v;
        }
        if (null !== $v = $this->languageCode) {
            if (!LanguageCode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "languageCode" for "%s". The value "%s" is not a valid "LanguageCode".', __CLASS__, $v));
            }
            $payload['languageCode'] = $v;
        }
        if (null !== $v = $this->languageDescription) {
            $payload['languageDescription'] = $v;
        }

        return $payload;
    }
}
