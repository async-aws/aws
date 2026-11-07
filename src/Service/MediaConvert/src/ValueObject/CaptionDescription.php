<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\CaptionSource;
use AsyncAws\MediaConvert\Enum\LanguageCode;

/**
 * This object holds groups of settings related to captions for one output. For each output that has captions, include
 * one instance of CaptionDescriptions.
 */
final class CaptionDescription
{
    /**
     * Specifies the audio selector or audio selector group that MediaConvert uses to generate Smart subtitles. The audio
     * selector you choose must exist in all inputs for the job. If MediaConvert can't detect speech in the audio, it can't
     * generate subtitles. Required when source is SMART_SUBTITLE; not allowed otherwise.
     *
     * @var string[]|null
     */
    private $audioSourceNames;

    /**
     * Specifies which "Caption Selector":#inputs-caption_selector to use from each input when generating captions. The name
     * should be of the format "Caption Selector <N>", which denotes that the Nth Caption Selector will be used from each
     * input.
     *
     * @var string|null
     */
    private $captionSelectorName;

    /**
     * Specify the language for this captions output track. For most captions output formats, the encoder puts this language
     * information in the output captions metadata. If your output captions format is DVB-Sub or Burn in, the encoder uses
     * this language information when automatically selecting the font script for rendering the captions text. For all
     * outputs, you can use an ISO 639-2 or ISO 639-3 code. For streaming outputs, you can also use any other code in the
     * full RFC-5646 specification. Streaming outputs are those that are in one of the following output groups: CMAF, DASH
     * ISO, Apple HLS, or Microsoft Smooth Streaming.
     *
     * @var string|null
     */
    private $customLanguageCode;

    /**
     * Settings related to one captions tab on the MediaConvert console. Usually, one captions tab corresponds to one output
     * captions track. Depending on your output captions format, one tab might correspond to a set of output captions
     * tracks. For more information, see https://docs.aws.amazon.com/mediaconvert/latest/ug/including-captions.html.
     *
     * @var CaptionDestinationSettings|null
     */
    private $destinationSettings;

    /**
     * Specify the language of this captions output track. For most captions output formats, the encoder puts this language
     * information in the output captions metadata. If your output captions format is DVB-Sub or Burn in, the encoder uses
     * this language information to choose the font language for rendering the captions text.
     *
     * @var LanguageCode::*|null
     */
    private $languageCode;

    /**
     * Specify a label for this set of output captions. For example, "English", "Director commentary", or "track_2". For
     * streaming outputs, MediaConvert passes this information into destination manifests for display on the end-viewer's
     * player device. For outputs in other output groups, the service ignores this setting.
     *
     * @var string|null
     */
    private $languageDescription;

    /**
     * Specify the source of the captions for this caption description. Choose Captions input source
     * (INPUT_CAPTION_SELECTOR) to use a caption selector from your input. Choose Smart subtitling (SMART_SUBTITLE) to have
     * MediaConvert and Elemental Inference generate subtitles automatically from an audio source that you specify.
     *
     * @var CaptionSource::*|null
     */
    private $source;

    /**
     * @param array{
     *   AudioSourceNames?: string[]|null,
     *   CaptionSelectorName?: string|null,
     *   CustomLanguageCode?: string|null,
     *   DestinationSettings?: CaptionDestinationSettings|array|null,
     *   LanguageCode?: LanguageCode::*|null,
     *   LanguageDescription?: string|null,
     *   Source?: CaptionSource::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->audioSourceNames = $input['AudioSourceNames'] ?? null;
        $this->captionSelectorName = $input['CaptionSelectorName'] ?? null;
        $this->customLanguageCode = $input['CustomLanguageCode'] ?? null;
        $this->destinationSettings = isset($input['DestinationSettings']) ? CaptionDestinationSettings::create($input['DestinationSettings']) : null;
        $this->languageCode = $input['LanguageCode'] ?? null;
        $this->languageDescription = $input['LanguageDescription'] ?? null;
        $this->source = $input['Source'] ?? null;
    }

    /**
     * @param array{
     *   AudioSourceNames?: string[]|null,
     *   CaptionSelectorName?: string|null,
     *   CustomLanguageCode?: string|null,
     *   DestinationSettings?: CaptionDestinationSettings|array|null,
     *   LanguageCode?: LanguageCode::*|null,
     *   LanguageDescription?: string|null,
     *   Source?: CaptionSource::*|null,
     * }|CaptionDescription $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getAudioSourceNames(): array
    {
        return $this->audioSourceNames ?? [];
    }

    public function getCaptionSelectorName(): ?string
    {
        return $this->captionSelectorName;
    }

    public function getCustomLanguageCode(): ?string
    {
        return $this->customLanguageCode;
    }

    public function getDestinationSettings(): ?CaptionDestinationSettings
    {
        return $this->destinationSettings;
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
     * @return CaptionSource::*|null
     */
    public function getSource(): ?string
    {
        return $this->source;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->audioSourceNames) {
            $index = -1;
            $payload['audioSourceNames'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['audioSourceNames'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->captionSelectorName) {
            $payload['captionSelectorName'] = $v;
        }
        if (null !== $v = $this->customLanguageCode) {
            $payload['customLanguageCode'] = $v;
        }
        if (null !== $v = $this->destinationSettings) {
            $payload['destinationSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->languageCode) {
            if (!LanguageCode::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "languageCode" for "%s". The value "%s" is not a valid "LanguageCode".', __CLASS__, $v));
            }
            $payload['languageCode'] = $v;
        }
        if (null !== $v = $this->languageDescription) {
            $payload['languageDescription'] = $v;
        }
        if (null !== $v = $this->source) {
            if (!CaptionSource::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "source" for "%s". The value "%s" is not a valid "CaptionSource".', __CLASS__, $v));
            }
            $payload['source'] = $v;
        }

        return $payload;
    }
}
