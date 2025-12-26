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
     * Specify a fallback audio selector for this input. Use to ensure outputs have audio even when the audio selector you
     * specify in your output is missing from the source. DEFAULT (Checked in the MediaConvert console): If your output
     * settings specify an audio selector that does not exist in this input, MediaConvert uses this audio selector instead.
     * This is useful when you have multiple inputs with a different number of audio tracks. NOT_DEFAULT (Unchecked in the
     * MediaConvert console): MediaConvert will not fallback from any missing audio selector. Any output specifying a
     * missing audio selector will be silent.
     *
     * @var AudioDefaultSelection::*|null
     */
    private $defaultSelection;

    /**
     * Specify the S3, HTTP, or HTTPS URL for your external audio file input.
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
     * Specify how MediaConvert selects audio content within your input. The default is Track. PID: Select audio by
     * specifying the Packet Identifier (PID) values for MPEG Transport Stream inputs. Use this when you know the exact PID
     * values of your audio streams. Track: Default. Select audio by track number. This is the most common option and works
     * with most input container formats. If more types of audio data get recognized in the future, these numberings may
     * shift, but the numberings used for Stream mode will not. Language code: Select audio by language using an ISO 639-2
     * or ISO 639-3 three-letter    code in all capital letters. Use this when your source has embedded language metadata
     * and you want to select tracks based on their language. HLS rendition group: Select audio from an HLS rendition group.
     * Use this when your input is an HLS package with multiple audio renditions and you want to select specific rendition
     * groups. All PCM: Select all uncompressed PCM audio tracks from your input automatically. This is useful when you want
     * to include all PCM audio tracks without specifying individual track numbers. Stream: Select audio by stream number.
     * Stream numbers include all tracks in the source file, regardless of type, and correspond to either the order of
     * tracks in the file, or if applicable, the stream number metadata of the track. Although all tracks count toward these
     * stream numbers, in this audio selector context, only the stream number of a track containing audio data may be used.
     * If your source file contains a track which is not recognized by the service, then the corresponding stream number
     * will still be reserved for future use. If more types of audio data get recognized in the future, these numberings
     * will not shift.
     *
     * @var AudioSelectorType::*|null
     */
    private $selectorType;

    /**
     * Identify a track from the input audio to include in this selector by entering the stream index number. These
     * numberings count all tracks in the input file, but only a track containing audio data may be used here. To include
     * several tracks in a single audio selector, specify multiple tracks as follows. Using the console, enter a
     * comma-separated list. For example, type "1,2,3" to include tracks 1 through 3.
     *
     * @var int[]|null
     */
    private $streams;

    /**
     * Identify a track from the input audio to include in this selector by entering the track index number. These
     * numberings include only tracks recognized as audio. If the service recognizes more types of audio tracks in the
     * future, these numberings may shift. To include several tracks in a single audio selector, specify multiple tracks as
     * follows. Using the console, enter a comma-separated list. For example, type "1,2,3" to include tracks 1 through 3.
     *
     * @var int[]|null
     */
    private $tracks;

    /**
     * @param array{
     *   AudioDurationCorrection?: AudioDurationCorrection::*|null,
     *   CustomLanguageCode?: string|null,
     *   DefaultSelection?: AudioDefaultSelection::*|null,
     *   ExternalAudioFileInput?: string|null,
     *   HlsRenditionGroupSettings?: HlsRenditionGroupSettings|array|null,
     *   LanguageCode?: LanguageCode::*|null,
     *   Offset?: int|null,
     *   Pids?: int[]|null,
     *   ProgramSelection?: int|null,
     *   RemixSettings?: RemixSettings|array|null,
     *   SelectorType?: AudioSelectorType::*|null,
     *   Streams?: int[]|null,
     *   Tracks?: int[]|null,
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
        $this->streams = $input['Streams'] ?? null;
        $this->tracks = $input['Tracks'] ?? null;
    }

    /**
     * @param array{
     *   AudioDurationCorrection?: AudioDurationCorrection::*|null,
     *   CustomLanguageCode?: string|null,
     *   DefaultSelection?: AudioDefaultSelection::*|null,
     *   ExternalAudioFileInput?: string|null,
     *   HlsRenditionGroupSettings?: HlsRenditionGroupSettings|array|null,
     *   LanguageCode?: LanguageCode::*|null,
     *   Offset?: int|null,
     *   Pids?: int[]|null,
     *   ProgramSelection?: int|null,
     *   RemixSettings?: RemixSettings|array|null,
     *   SelectorType?: AudioSelectorType::*|null,
     *   Streams?: int[]|null,
     *   Tracks?: int[]|null,
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
    public function getStreams(): array
    {
        return $this->streams ?? [];
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
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "audioDurationCorrection" for "%s". The value "%s" is not a valid "AudioDurationCorrection".', __CLASS__, $v));
            }
            $payload['audioDurationCorrection'] = $v;
        }
        if (null !== $v = $this->customLanguageCode) {
            $payload['customLanguageCode'] = $v;
        }
        if (null !== $v = $this->defaultSelection) {
            if (!AudioDefaultSelection::exists($v)) {
                /** @psalm-suppress NoValue */
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
                /** @psalm-suppress NoValue */
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
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "selectorType" for "%s". The value "%s" is not a valid "AudioSelectorType".', __CLASS__, $v));
            }
            $payload['selectorType'] = $v;
        }
        if (null !== $v = $this->streams) {
            $index = -1;
            $payload['streams'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['streams'][$index] = $listValue;
            }
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
