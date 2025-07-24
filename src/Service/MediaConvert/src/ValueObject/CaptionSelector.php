<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\LanguageCode;

/**
 * Use captions selectors to specify the captions data from your input that you use in your outputs. You can use up to
 * 100 captions selectors per input.
 */
final class CaptionSelector
{
    /**
     * The specific language to extract from source, using the ISO 639-2 or ISO 639-3 three-letter language code. If input
     * is SCTE-27, complete this field and/or PID to select the caption language to extract. If input is DVB-Sub and output
     * is Burn-in, complete this field and/or PID to select the caption language to extract. If input is DVB-Sub that is
     * being passed through, omit this field (and PID field); there is no way to extract a specific language with
     * pass-through captions.
     *
     * @var string|null
     */
    private $customLanguageCode;

    /**
     * The specific language to extract from source. If input is SCTE-27, complete this field and/or PID to select the
     * caption language to extract. If input is DVB-Sub and output is Burn-in, complete this field and/or PID to select the
     * caption language to extract. If input is DVB-Sub that is being passed through, omit this field (and PID field); there
     * is no way to extract a specific language with pass-through captions.
     *
     * @var LanguageCode::*|string|null
     */
    private $languageCode;

    /**
     * If your input captions are SCC, TTML, STL, SMI, SRT, or IMSC in an xml file, specify the URI of the input captions
     * source file. If your input captions are IMSC in an IMF package, use TrackSourceSettings instead of FileSoureSettings.
     *
     * @var CaptionSourceSettings|null
     */
    private $sourceSettings;

    /**
     * @param array{
     *   CustomLanguageCode?: null|string,
     *   LanguageCode?: null|LanguageCode::*|string,
     *   SourceSettings?: null|CaptionSourceSettings|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->customLanguageCode = $input['CustomLanguageCode'] ?? null;
        $this->languageCode = $input['LanguageCode'] ?? null;
        $this->sourceSettings = isset($input['SourceSettings']) ? CaptionSourceSettings::create($input['SourceSettings']) : null;
    }

    /**
     * @param array{
     *   CustomLanguageCode?: null|string,
     *   LanguageCode?: null|LanguageCode::*|string,
     *   SourceSettings?: null|CaptionSourceSettings|array,
     * }|CaptionSelector $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCustomLanguageCode(): ?string
    {
        return $this->customLanguageCode;
    }

    /**
     * @return LanguageCode::*|string|null
     */
    public function getLanguageCode(): ?string
    {
        return $this->languageCode;
    }

    public function getSourceSettings(): ?CaptionSourceSettings
    {
        return $this->sourceSettings;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->customLanguageCode) {
            $payload['customLanguageCode'] = $v;
        }
        if (null !== $v = $this->languageCode) {
            if (!LanguageCode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "languageCode" for "%s". The value "%s" is not a valid "LanguageCode".', __CLASS__, $v));
            }
            $payload['languageCode'] = $v;
        }
        if (null !== $v = $this->sourceSettings) {
            $payload['sourceSettings'] = $v->requestBody();
        }

        return $payload;
    }
}
