<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\MotionImageInsertionMode;
use AsyncAws\MediaConvert\Enum\MotionImagePlayback;

/**
 * Overlay motion graphics on top of your video. The motion graphics that you specify here appear on all outputs in all
 * output groups. For more information, see
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/motion-graphic-overlay.html.
 */
final class MotionImageInserter
{
    /**
     * If your motion graphic asset is a .mov file, keep this setting unspecified. If your motion graphic asset is a series
     * of .png files, specify the frame rate of the overlay in frames per second, as a fraction. For example, specify 24 fps
     * as 24/1. Make sure that the number of images in your series matches the frame rate and your intended overlay
     * duration. For example, if you want a 30-second overlay at 30 fps, you should have 900 .png images. This overlay frame
     * rate doesn't need to match the frame rate of the underlying video.
     *
     * @var MotionImageInsertionFramerate|null
     */
    private $framerate;

    /**
     * Specify the .mov file or series of .png files that you want to overlay on your video. For .png files, provide the
     * file name of the first file in the series. Make sure that the names of the .png files end with sequential numbers
     * that specify the order that they are played in. For example, overlay_000.png, overlay_001.png, overlay_002.png, and
     * so on. The sequence must start at zero, and each image file name must have the same number of digits. Pad your
     * initial file names with enough zeros to complete the sequence. For example, if the first image is overlay_0.png,
     * there can be only 10 images in the sequence, with the last image being overlay_9.png. But if the first image is
     * overlay_00.png, there can be 100 images in the sequence.
     *
     * @var string|null
     */
    private $input;

    /**
     * Choose the type of motion graphic asset that you are providing for your overlay. You can choose either a .mov file or
     * a series of .png files.
     *
     * @var MotionImageInsertionMode::*|null
     */
    private $insertionMode;

    /**
     * Use Offset to specify the placement of your motion graphic overlay on the video frame. Specify in pixels, from the
     * upper-left corner of the frame. If you don't specify an offset, the service scales your overlay to the full size of
     * the frame. Otherwise, the service inserts the overlay at its native resolution and scales the size up or down with
     * any video scaling.
     *
     * @var MotionImageInsertionOffset|null
     */
    private $offset;

    /**
     * Specify whether your motion graphic overlay repeats on a loop or plays only once.
     *
     * @var MotionImagePlayback::*|null
     */
    private $playback;

    /**
     * Specify when the motion overlay begins. Use timecode format (HH:MM:SS:FF or HH:MM:SS;FF). Make sure that the timecode
     * you provide here takes into account how you have set up your timecode configuration under both job settings and input
     * settings. The simplest way to do that is to set both to start at 0. If you need to set up your job to follow
     * timecodes embedded in your source that don't start at zero, make sure that you specify a start time that is after the
     * first embedded timecode. For more information, see
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/setting-up-timecode.html.
     *
     * @var string|null
     */
    private $startTime;

    /**
     * @param array{
     *   Framerate?: MotionImageInsertionFramerate|array|null,
     *   Input?: string|null,
     *   InsertionMode?: MotionImageInsertionMode::*|null,
     *   Offset?: MotionImageInsertionOffset|array|null,
     *   Playback?: MotionImagePlayback::*|null,
     *   StartTime?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->framerate = isset($input['Framerate']) ? MotionImageInsertionFramerate::create($input['Framerate']) : null;
        $this->input = $input['Input'] ?? null;
        $this->insertionMode = $input['InsertionMode'] ?? null;
        $this->offset = isset($input['Offset']) ? MotionImageInsertionOffset::create($input['Offset']) : null;
        $this->playback = $input['Playback'] ?? null;
        $this->startTime = $input['StartTime'] ?? null;
    }

    /**
     * @param array{
     *   Framerate?: MotionImageInsertionFramerate|array|null,
     *   Input?: string|null,
     *   InsertionMode?: MotionImageInsertionMode::*|null,
     *   Offset?: MotionImageInsertionOffset|array|null,
     *   Playback?: MotionImagePlayback::*|null,
     *   StartTime?: string|null,
     * }|MotionImageInserter $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFramerate(): ?MotionImageInsertionFramerate
    {
        return $this->framerate;
    }

    public function getInput(): ?string
    {
        return $this->input;
    }

    /**
     * @return MotionImageInsertionMode::*|null
     */
    public function getInsertionMode(): ?string
    {
        return $this->insertionMode;
    }

    public function getOffset(): ?MotionImageInsertionOffset
    {
        return $this->offset;
    }

    /**
     * @return MotionImagePlayback::*|null
     */
    public function getPlayback(): ?string
    {
        return $this->playback;
    }

    public function getStartTime(): ?string
    {
        return $this->startTime;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->framerate) {
            $payload['framerate'] = $v->requestBody();
        }
        if (null !== $v = $this->input) {
            $payload['input'] = $v;
        }
        if (null !== $v = $this->insertionMode) {
            if (!MotionImageInsertionMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "insertionMode" for "%s". The value "%s" is not a valid "MotionImageInsertionMode".', __CLASS__, $v));
            }
            $payload['insertionMode'] = $v;
        }
        if (null !== $v = $this->offset) {
            $payload['offset'] = $v->requestBody();
        }
        if (null !== $v = $this->playback) {
            if (!MotionImagePlayback::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "playback" for "%s". The value "%s" is not a valid "MotionImagePlayback".', __CLASS__, $v));
            }
            $payload['playback'] = $v;
        }
        if (null !== $v = $this->startTime) {
            $payload['startTime'] = $v;
        }

        return $payload;
    }
}
