<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\InputTimecodeSource;

/**
 * Input settings for Video overlay. You can include one or more video overlays in sequence at different times that you
 * specify.
 */
final class VideoOverlayInput
{
    /**
     * Use Audio selectors to specify audio to use during your Video overlay. You can use multiple Audio selectors per Video
     * overlay. When you include an Audio selector within a Video overlay, MediaConvert mutes any Audio selectors with the
     * same name from the underlying input. For example, if your underlying input has Audio selector 1 and Audio selector 2,
     * and your Video overlay only has Audio selector 1, then MediaConvert replaces all audio for Audio selector 1 during
     * the Video overlay. To replace all audio for all Audio selectors from the underlying input by using a single Audio
     * selector in your overlay, set DefaultSelection to DEFAULT (Check \"Use as default\" in the MediaConvert console).
     *
     * @var array<string, AudioSelector>|null
     */
    private $audioSelectors;

    /**
     * Specify the input file S3, HTTP, or HTTPS URL for your video overlay.
     * To specify one or more Transitions for your base input video instead: Leave blank.
     *
     * @var string|null
     */
    private $fileInput;

    /**
     * Specify one or more clips to use from your video overlay. When you include an input clip, you must also specify its
     * start timecode, end timecode, or both start and end timecode.
     *
     * @var VideoOverlayInputClipping[]|null
     */
    private $inputClippings;

    /**
     * Specify the timecode source for your video overlay input clips. To use the timecode present in your video overlay:
     * Choose Embedded. To use a zerobased timecode: Choose Start at 0. To choose a timecode: Choose Specified start. When
     * you do, enter the starting timecode in Start timecode. If you don't specify a value for Timecode source, MediaConvert
     * uses Embedded by default.
     *
     * @var InputTimecodeSource::*|null
     */
    private $timecodeSource;

    /**
     * Specify the starting timecode for this video overlay. To use this setting, you must set Timecode source to Specified
     * start.
     *
     * @var string|null
     */
    private $timecodeStart;

    /**
     * @param array{
     *   AudioSelectors?: array<string, AudioSelector|array>|null,
     *   FileInput?: string|null,
     *   InputClippings?: array<VideoOverlayInputClipping|array>|null,
     *   TimecodeSource?: InputTimecodeSource::*|null,
     *   TimecodeStart?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->audioSelectors = isset($input['AudioSelectors']) ? array_map([AudioSelector::class, 'create'], $input['AudioSelectors']) : null;
        $this->fileInput = $input['FileInput'] ?? null;
        $this->inputClippings = isset($input['InputClippings']) ? array_map([VideoOverlayInputClipping::class, 'create'], $input['InputClippings']) : null;
        $this->timecodeSource = $input['TimecodeSource'] ?? null;
        $this->timecodeStart = $input['TimecodeStart'] ?? null;
    }

    /**
     * @param array{
     *   AudioSelectors?: array<string, AudioSelector|array>|null,
     *   FileInput?: string|null,
     *   InputClippings?: array<VideoOverlayInputClipping|array>|null,
     *   TimecodeSource?: InputTimecodeSource::*|null,
     *   TimecodeStart?: string|null,
     * }|VideoOverlayInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, AudioSelector>
     */
    public function getAudioSelectors(): array
    {
        return $this->audioSelectors ?? [];
    }

    public function getFileInput(): ?string
    {
        return $this->fileInput;
    }

    /**
     * @return VideoOverlayInputClipping[]
     */
    public function getInputClippings(): array
    {
        return $this->inputClippings ?? [];
    }

    /**
     * @return InputTimecodeSource::*|null
     */
    public function getTimecodeSource(): ?string
    {
        return $this->timecodeSource;
    }

    public function getTimecodeStart(): ?string
    {
        return $this->timecodeStart;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->audioSelectors) {
            if (empty($v)) {
                $payload['audioSelectors'] = new \stdClass();
            } else {
                $payload['audioSelectors'] = [];
                foreach ($v as $name => $mv) {
                    $payload['audioSelectors'][$name] = $mv->requestBody();
                }
            }
        }
        if (null !== $v = $this->fileInput) {
            $payload['fileInput'] = $v;
        }
        if (null !== $v = $this->inputClippings) {
            $index = -1;
            $payload['inputClippings'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['inputClippings'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->timecodeSource) {
            if (!InputTimecodeSource::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "timecodeSource" for "%s". The value "%s" is not a valid "InputTimecodeSource".', __CLASS__, $v));
            }
            $payload['timecodeSource'] = $v;
        }
        if (null !== $v = $this->timecodeStart) {
            $payload['timecodeStart'] = $v;
        }

        return $payload;
    }
}
