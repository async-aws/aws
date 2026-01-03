<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\AudioDurationCorrection;
use AsyncAws\MediaConvert\Enum\DynamicAudioSelectorType;
use AsyncAws\MediaConvert\Enum\LanguageCode;

/**
 * Use Dynamic audio selectors when you do not know the track layout of your source when you submit your job, but want
 * to select multiple audio tracks. When you include an audio track in your output and specify this Dynamic audio
 * selector as the Audio source, MediaConvert creates an audio track within that output for each dynamically selected
 * track. Note that when you include a Dynamic audio selector for two or more inputs, each input must have the same
 * number of audio tracks and audio channels.
 */
final class DynamicAudioSelector
{
    /**
     * Apply audio timing corrections to help synchronize audio and video in your output. To apply timing corrections, your
     * input must meet the following requirements: * Container: MP4, or MOV, with an accurate time-to-sample (STTS) table. *
     * Audio track: AAC. Choose from the following audio timing correction settings: * Disabled (Default): Apply no
     * correction. * Auto: Recommended for most inputs. MediaConvert analyzes the audio timing in your input and determines
     * which correction setting to use, if needed. * Track: Adjust the duration of each audio frame by a constant amount to
     * align the audio track length with STTS duration. Track-level correction does not affect pitch, and is recommended for
     * tonal audio content such as music. * Frame: Adjust the duration of each audio frame by a variable amount to align
     * audio frames with STTS timestamps. No corrections are made to already-aligned frames. Frame-level correction may
     * affect the pitch of corrected frames, and is recommended for atonal audio content such as speech or percussion. *
     * Force: Apply audio duration correction, either Track or Frame depending on your input, regardless of the accuracy of
     * your input's STTS table. Your output audio and video may not be aligned or it may contain audio artifacts.
     *
     * @var AudioDurationCorrection::*|null
     */
    private $audioDurationCorrection;

    /**
     * Specify the S3, HTTP, or HTTPS URL for your external audio file input.
     *
     * @var string|null
     */
    private $externalAudioFileInput;

    /**
     * Specify the language, using an ISO 639-2 three-letter code in all capital letters. You can find a list of codes at:
     * https://www.loc.gov/standards/iso639-2/php/code_list.php
     *
     * @var LanguageCode::*|null
     */
    private $languageCode;

    /**
     * Specify a time delta, in milliseconds, to offset the audio from the input video.
     * To specify no offset: Keep the default value, 0.
     * To specify an offset: Enter an integer from -2147483648 to 2147483647.
     *
     * @var int|null
     */
    private $offset;

    /**
     * Specify which audio tracks to dynamically select from your source. To select all audio tracks: Keep the default
     * value, All tracks. To select all audio tracks with a specific language code: Choose Language code. When you do, you
     * must also specify a language code under the Language code setting. If there is no matching Language code in your
     * source, then no track will be selected.
     *
     * @var DynamicAudioSelectorType::*|null
     */
    private $selectorType;

    /**
     * @param array{
     *   AudioDurationCorrection?: AudioDurationCorrection::*|null,
     *   ExternalAudioFileInput?: string|null,
     *   LanguageCode?: LanguageCode::*|null,
     *   Offset?: int|null,
     *   SelectorType?: DynamicAudioSelectorType::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->audioDurationCorrection = $input['AudioDurationCorrection'] ?? null;
        $this->externalAudioFileInput = $input['ExternalAudioFileInput'] ?? null;
        $this->languageCode = $input['LanguageCode'] ?? null;
        $this->offset = $input['Offset'] ?? null;
        $this->selectorType = $input['SelectorType'] ?? null;
    }

    /**
     * @param array{
     *   AudioDurationCorrection?: AudioDurationCorrection::*|null,
     *   ExternalAudioFileInput?: string|null,
     *   LanguageCode?: LanguageCode::*|null,
     *   Offset?: int|null,
     *   SelectorType?: DynamicAudioSelectorType::*|null,
     * }|DynamicAudioSelector $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AudioDurationCorrection::*|null
     */
    public function getAudioDurationCorrection(): ?string
    {
        return $this->audioDurationCorrection;
    }

    public function getExternalAudioFileInput(): ?string
    {
        return $this->externalAudioFileInput;
    }

    /**
     * @return LanguageCode::*|null
     */
    public function getLanguageCode(): ?string
    {
        return $this->languageCode;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @return DynamicAudioSelectorType::*|null
     */
    public function getSelectorType(): ?string
    {
        return $this->selectorType;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->audioDurationCorrection) {
            if (!AudioDurationCorrection::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "audioDurationCorrection" for "%s". The value "%s" is not a valid "AudioDurationCorrection".', __CLASS__, $v));
            }
            $payload['audioDurationCorrection'] = $v;
        }
        if (null !== $v = $this->externalAudioFileInput) {
            $payload['externalAudioFileInput'] = $v;
        }
        if (null !== $v = $this->languageCode) {
            if (!LanguageCode::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "languageCode" for "%s". The value "%s" is not a valid "LanguageCode".', __CLASS__, $v));
            }
            $payload['languageCode'] = $v;
        }
        if (null !== $v = $this->offset) {
            $payload['offset'] = $v;
        }
        if (null !== $v = $this->selectorType) {
            if (!DynamicAudioSelectorType::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "selectorType" for "%s". The value "%s" is not a valid "DynamicAudioSelectorType".', __CLASS__, $v));
            }
            $payload['selectorType'] = $v;
        }

        return $payload;
    }
}
