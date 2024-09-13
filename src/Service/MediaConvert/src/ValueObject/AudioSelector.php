<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\AudioDefaultSelection;
use AsyncAws\MediaConvert\Enum\AudioDurationCorrection;
use AsyncAws\MediaConvert\Enum\AudioSelectorType;
use AsyncAws\MediaConvert\Enum\LanguageCode;

/**
 * Use Audio selectors to specify a track or set of tracks from the input that you will use in your outputs. You can use
 * multiple Audio selectors per input.
 */
final class AudioSelector
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
     * Selects a specific language code from within an audio source, using the ISO 639-2 or ISO 639-3 three-letter language
     * code.
     *
     * @var string|null
     */
    private $customLanguageCode;

    /**
     * Enable this setting on one audio selector to set it as the default for the job. The service uses this default for
     * outputs where it can't find the specified input audio. If you don't set a default, those outputs have no audio.
     *
     * @var AudioDefaultSelection::*|null
     */
    private $defaultSelection;

    /**
     * Specifies audio data from an external file source.
     *
     * @var string|null
     */
    private $externalAudioFileInput;

    /**
     * Settings specific to audio sources in an HLS alternate rendition group. Specify the properties (renditionGroupId,
     * renditionName or renditionLanguageCode) to identify the unique audio track among the alternative rendition groups
     * present in the HLS manifest. If no unique track is found, or multiple tracks match the properties provided, the job
     * fails. If no properties in hlsRenditionGroupSettings are specified, the default audio track within the video segment
     * is chosen. If there is no audio within video segment, the alternative audio with DEFAULT=YES is chosen instead.
     *
     * @var HlsRenditionGroupSettings|null
     */
    private $hlsRenditionGroupSettings;

    /**
     * Selects a specific language code from within an audio source.
     *
     * @var LanguageCode::*|null
     */
    private $languageCode;

    /**
     * Specifies a time delta in milliseconds to offset the audio from the input video.
     *
     * @var int|null
     */
    private $offset;

    /**
     * Selects a specific PID from within an audio source (e.g. 257 selects PID 0x101).
     *
     * @var int[]|null
     */
    private $pids;

    /**
     * Use this setting for input streams that contain Dolby E, to have the service extract specific program data from the
     * track. To select multiple programs, create multiple selectors with the same Track and different Program numbers. In
     * the console, this setting is visible when you set Selector type to Track. Choose the program number from the dropdown
     * list. If your input file has incorrect metadata, you can choose All channels instead of a program number to have the
     * service ignore the program IDs and include all the programs in the track.
     *
     * @var int|null
     */
    private $programSelection;

    /**
     * Use these settings to reorder the audio channels of one input to match those of another input. This allows you to
     * combine the two files into a single output, one after the other.
     *
     * @var RemixSettings|null
     */
    private $remixSettings;

    /**
     * Specifies the type of the audio selector.
     *
     * @var AudioSelectorType::*|null
     */
    private $selectorType;

    /**
     * Identify a track from the input audio to include in this selector by entering the track index number. To include
     * several tracks in a single audio selector, specify multiple tracks as follows. Using the console, enter a
     * comma-separated list. For example, type "1,2,3" to include tracks 1 through 3.
     *
     * @var int[]|null
     */
    private $tracks;

    /**
     * @param array{
     *   AudioDurationCorrection?: null|AudioDurationCorrection::*,
     *   CustomLanguageCode?: null|string,
     *   DefaultSelection?: null|AudioDefaultSelection::*,
     *   ExternalAudioFileInput?: null|string,
     *   HlsRenditionGroupSettings?: null|HlsRenditionGroupSettings|array,
     *   LanguageCode?: null|LanguageCode::*,
     *   Offset?: null|int,
     *   Pids?: null|int[],
     *   ProgramSelection?: null|int,
     *   RemixSettings?: null|RemixSettings|array,
     *   SelectorType?: null|AudioSelectorType::*,
     *   Tracks?: null|int[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->audioDurationCorrection = $input['AudioDurationCorrection'] ?? null;
        $this->customLanguageCode = $input['CustomLanguageCode'] ?? null;
        $this->defaultSelection = $input['DefaultSelection'] ?? null;
        $this->externalAudioFileInput = $input['ExternalAudioFileInput'] ?? null;
        $this->hlsRenditionGroupSettings = isset($input['HlsRenditionGroupSettings']) ? HlsRenditionGroupSettings::create($input['HlsRenditionGroupSettings']) : null;
        $this->languageCode = $input['LanguageCode'] ?? null;
        $this->offset = $input['Offset'] ?? null;
        $this->pids = $input['Pids'] ?? null;
        $this->programSelection = $input['ProgramSelection'] ?? null;
        $this->remixSettings = isset($input['RemixSettings']) ? RemixSettings::create($input['RemixSettings']) : null;
        $this->selectorType = $input['SelectorType'] ?? null;
        $this->tracks = $input['Tracks'] ?? null;
    }

    /**
     * @param array{
     *   AudioDurationCorrection?: null|AudioDurationCorrection::*,
     *   CustomLanguageCode?: null|string,
     *   DefaultSelection?: null|AudioDefaultSelection::*,
     *   ExternalAudioFileInput?: null|string,
     *   HlsRenditionGroupSettings?: null|HlsRenditionGroupSettings|array,
     *   LanguageCode?: null|LanguageCode::*,
     *   Offset?: null|int,
     *   Pids?: null|int[],
     *   ProgramSelection?: null|int,
     *   RemixSettings?: null|RemixSettings|array,
     *   SelectorType?: null|AudioSelectorType::*,
     *   Tracks?: null|int[],
     * }|AudioSelector $input
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

    public function getCustomLanguageCode(): ?string
    {
        return $this->customLanguageCode;
    }

    /**
     * @return AudioDefaultSelection::*|null
     */
    public function getDefaultSelection(): ?string
    {
        return $this->defaultSelection;
    }

    public function getExternalAudioFileInput(): ?string
    {
        return $this->externalAudioFileInput;
    }

    public function getHlsRenditionGroupSettings(): ?HlsRenditionGroupSettings
    {
        return $this->hlsRenditionGroupSettings;
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
     * @return int[]
     */
    public function getPids(): array
    {
        return $this->pids ?? [];
    }

    public function getProgramSelection(): ?int
    {
        return $this->programSelection;
    }

    public function getRemixSettings(): ?RemixSettings
    {
        return $this->remixSettings;
    }

    /**
     * @return AudioSelectorType::*|null
     */
    public function getSelectorType(): ?string
    {
        return $this->selectorType;
    }

    /**
     * @return int[]
     */
    public function getTracks(): array
    {
        return $this->tracks ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->audioDurationCorrection) {
            if (!AudioDurationCorrection::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "audioDurationCorrection" for "%s". The value "%s" is not a valid "AudioDurationCorrection".', __CLASS__, $v));
            }
            $payload['audioDurationCorrection'] = $v;
        }
        if (null !== $v = $this->customLanguageCode) {
            $payload['customLanguageCode'] = $v;
        }
        if (null !== $v = $this->defaultSelection) {
            if (!AudioDefaultSelection::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "defaultSelection" for "%s". The value "%s" is not a valid "AudioDefaultSelection".', __CLASS__, $v));
            }
            $payload['defaultSelection'] = $v;
        }
        if (null !== $v = $this->externalAudioFileInput) {
            $payload['externalAudioFileInput'] = $v;
        }
        if (null !== $v = $this->hlsRenditionGroupSettings) {
            $payload['hlsRenditionGroupSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->languageCode) {
            if (!LanguageCode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "languageCode" for "%s". The value "%s" is not a valid "LanguageCode".', __CLASS__, $v));
            }
            $payload['languageCode'] = $v;
        }
        if (null !== $v = $this->offset) {
            $payload['offset'] = $v;
        }
        if (null !== $v = $this->pids) {
            $index = -1;
            $payload['pids'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['pids'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->programSelection) {
            $payload['programSelection'] = $v;
        }
        if (null !== $v = $this->remixSettings) {
            $payload['remixSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->selectorType) {
            if (!AudioSelectorType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "selectorType" for "%s". The value "%s" is not a valid "AudioSelectorType".', __CLASS__, $v));
            }
            $payload['selectorType'] = $v;
        }
        if (null !== $v = $this->tracks) {
            $index = -1;
            $payload['tracks'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['tracks'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
