<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\AudioLanguageCodeControl;
use AsyncAws\MediaConvert\Enum\AudioTypeControl;
use AsyncAws\MediaConvert\Enum\LanguageCode;

/**
 * Settings related to one audio tab on the MediaConvert console. In your job JSON, an instance of AudioDescription is
 * equivalent to one audio tab in the console. Usually, one audio tab corresponds to one output audio track. Depending
 * on how you set up your input audio selectors and whether you use audio selector groups, one audio tab can correspond
 * to a group of output audio tracks.
 */
final class AudioDescription
{
    /**
     * Specify the QuickTime audio channel layout tags for the audio channels in this audio track. When you don't specify a
     * value, MediaConvert labels your track as Center (C) by default. To use Audio layout tagging, your output must be in a
     * QuickTime (MOV) container and your audio codec must be AAC, WAV, or AIFF.
     *
     * @var AudioChannelTaggingSettings|null
     */
    private $audioChannelTaggingSettings;

    /**
     * Advanced audio normalization settings. Ignore these settings unless you need to comply with a loudness standard.
     *
     * @var AudioNormalizationSettings|null
     */
    private $audioNormalizationSettings;

    /**
     * Specifies which audio data to use from each input. In the simplest case, specify an "Audio
     * Selector":#inputs-audio_selector by name based on its order within each input. For example if you specify "Audio
     * Selector 3", then the third audio selector will be used from each input. If an input does not have an "Audio Selector
     * 3", then the audio selector marked as "default" in that input will be used. If there is no audio selector marked as
     * "default", silence will be inserted for the duration of that input. Alternatively, an "Audio Selector
     * Group":#inputs-audio_selector_group name may be specified, with similar default/silence behavior. If no
     * audio_source_name is specified, then "Audio Selector 1" will be chosen automatically.
     *
     * @var string|null
     */
    private $audioSourceName;

    /**
     * Applies only if Follow Input Audio Type is unchecked (false). A number between 0 and 255. The following are defined
     * in ISO-IEC 13818-1: 0 = Undefined, 1 = Clean Effects, 2 = Hearing Impaired, 3 = Visually Impaired Commentary, 4-255 =
     * Reserved.
     *
     * @var int|null
     */
    private $audioType;

    /**
     * When set to FOLLOW_INPUT, if the input contains an ISO 639 audio_type, then that value is passed through to the
     * output. If the input contains no ISO 639 audio_type, the value in Audio Type is included in the output. Otherwise the
     * value in Audio Type is included in the output. Note that this field and audioType are both ignored if
     * audioDescriptionBroadcasterMix is set to BROADCASTER_MIXED_AD.
     *
     * @var AudioTypeControl::*|null
     */
    private $audioTypeControl;

    /**
     * Settings related to audio encoding. The settings in this group vary depending on the value that you choose for your
     * audio codec.
     *
     * @var AudioCodecSettings|null
     */
    private $codecSettings;

    /**
     * Specify the language for this audio output track. The service puts this language code into your output audio track
     * when you set Language code control to Use configured. The service also uses your specified custom language code when
     * you set Language code control to Follow input, but your input file doesn't specify a language code. For all outputs,
     * you can use an ISO 639-2 or ISO 639-3 code. For streaming outputs, you can also use any other code in the full
     * RFC-5646 specification. Streaming outputs are those that are in one of the following output groups: CMAF, DASH ISO,
     * Apple HLS, or Microsoft Smooth Streaming.
     *
     * @var string|null
     */
    private $customLanguageCode;

    /**
     * Indicates the language of the audio output track. The ISO 639 language specified in the 'Language Code' drop down
     * will be used when 'Follow Input Language Code' is not selected or when 'Follow Input Language Code' is selected but
     * there is no ISO 639 language code specified by the input.
     *
     * @var LanguageCode::*|null
     */
    private $languageCode;

    /**
     * Specify which source for language code takes precedence for this audio track. When you choose Follow input, the
     * service uses the language code from the input track if it's present. If there's no languge code on the input track,
     * the service uses the code that you specify in the setting Language code. When you choose Use configured, the service
     * uses the language code that you specify.
     *
     * @var AudioLanguageCodeControl::*|null
     */
    private $languageCodeControl;

    /**
     * Advanced audio remixing settings.
     *
     * @var RemixSettings|null
     */
    private $remixSettings;

    /**
     * Specify a label for this output audio stream. For example, "English", "Director commentary", or "track_2". For
     * streaming outputs, MediaConvert passes this information into destination manifests for display on the end-viewer's
     * player device. For outputs in other output groups, the service ignores this setting.
     *
     * @var string|null
     */
    private $streamName;

    /**
     * @param array{
     *   AudioChannelTaggingSettings?: AudioChannelTaggingSettings|array|null,
     *   AudioNormalizationSettings?: AudioNormalizationSettings|array|null,
     *   AudioSourceName?: string|null,
     *   AudioType?: int|null,
     *   AudioTypeControl?: AudioTypeControl::*|null,
     *   CodecSettings?: AudioCodecSettings|array|null,
     *   CustomLanguageCode?: string|null,
     *   LanguageCode?: LanguageCode::*|null,
     *   LanguageCodeControl?: AudioLanguageCodeControl::*|null,
     *   RemixSettings?: RemixSettings|array|null,
     *   StreamName?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->audioChannelTaggingSettings = isset($input['AudioChannelTaggingSettings']) ? AudioChannelTaggingSettings::create($input['AudioChannelTaggingSettings']) : null;
        $this->audioNormalizationSettings = isset($input['AudioNormalizationSettings']) ? AudioNormalizationSettings::create($input['AudioNormalizationSettings']) : null;
        $this->audioSourceName = $input['AudioSourceName'] ?? null;
        $this->audioType = $input['AudioType'] ?? null;
        $this->audioTypeControl = $input['AudioTypeControl'] ?? null;
        $this->codecSettings = isset($input['CodecSettings']) ? AudioCodecSettings::create($input['CodecSettings']) : null;
        $this->customLanguageCode = $input['CustomLanguageCode'] ?? null;
        $this->languageCode = $input['LanguageCode'] ?? null;
        $this->languageCodeControl = $input['LanguageCodeControl'] ?? null;
        $this->remixSettings = isset($input['RemixSettings']) ? RemixSettings::create($input['RemixSettings']) : null;
        $this->streamName = $input['StreamName'] ?? null;
    }

    /**
     * @param array{
     *   AudioChannelTaggingSettings?: AudioChannelTaggingSettings|array|null,
     *   AudioNormalizationSettings?: AudioNormalizationSettings|array|null,
     *   AudioSourceName?: string|null,
     *   AudioType?: int|null,
     *   AudioTypeControl?: AudioTypeControl::*|null,
     *   CodecSettings?: AudioCodecSettings|array|null,
     *   CustomLanguageCode?: string|null,
     *   LanguageCode?: LanguageCode::*|null,
     *   LanguageCodeControl?: AudioLanguageCodeControl::*|null,
     *   RemixSettings?: RemixSettings|array|null,
     *   StreamName?: string|null,
     * }|AudioDescription $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAudioChannelTaggingSettings(): ?AudioChannelTaggingSettings
    {
        return $this->audioChannelTaggingSettings;
    }

    public function getAudioNormalizationSettings(): ?AudioNormalizationSettings
    {
        return $this->audioNormalizationSettings;
    }

    public function getAudioSourceName(): ?string
    {
        return $this->audioSourceName;
    }

    public function getAudioType(): ?int
    {
        return $this->audioType;
    }

    /**
     * @return AudioTypeControl::*|null
     */
    public function getAudioTypeControl(): ?string
    {
        return $this->audioTypeControl;
    }

    public function getCodecSettings(): ?AudioCodecSettings
    {
        return $this->codecSettings;
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

    /**
     * @return AudioLanguageCodeControl::*|null
     */
    public function getLanguageCodeControl(): ?string
    {
        return $this->languageCodeControl;
    }

    public function getRemixSettings(): ?RemixSettings
    {
        return $this->remixSettings;
    }

    public function getStreamName(): ?string
    {
        return $this->streamName;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->audioChannelTaggingSettings) {
            $payload['audioChannelTaggingSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->audioNormalizationSettings) {
            $payload['audioNormalizationSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->audioSourceName) {
            $payload['audioSourceName'] = $v;
        }
        if (null !== $v = $this->audioType) {
            $payload['audioType'] = $v;
        }
        if (null !== $v = $this->audioTypeControl) {
            if (!AudioTypeControl::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "audioTypeControl" for "%s". The value "%s" is not a valid "AudioTypeControl".', __CLASS__, $v));
            }
            $payload['audioTypeControl'] = $v;
        }
        if (null !== $v = $this->codecSettings) {
            $payload['codecSettings'] = $v->requestBody();
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
        if (null !== $v = $this->languageCodeControl) {
            if (!AudioLanguageCodeControl::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "languageCodeControl" for "%s". The value "%s" is not a valid "AudioLanguageCodeControl".', __CLASS__, $v));
            }
            $payload['languageCodeControl'] = $v;
        }
        if (null !== $v = $this->remixSettings) {
            $payload['remixSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->streamName) {
            $payload['streamName'] = $v;
        }

        return $payload;
    }
}
