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
     *   FileInput?: string|null,
     *   InputClippings?: array<VideoOverlayInputClipping|array>|null,
     *   TimecodeSource?: InputTimecodeSource::*|null,
     *   TimecodeStart?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->fileInput = $input['FileInput'] ?? null;
        $this->inputClippings = isset($input['InputClippings']) ? array_map([VideoOverlayInputClipping::class, 'create'], $input['InputClippings']) : null;
        $this->timecodeSource = $input['TimecodeSource'] ?? null;
        $this->timecodeStart = $input['TimecodeStart'] ?? null;
    }

    /**
     * @param array{
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
