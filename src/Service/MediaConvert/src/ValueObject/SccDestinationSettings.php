<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\SccDestinationFramerate;

/**
 * Settings related to SCC captions. SCC is a sidecar format that holds captions in a file that is separate from the
 * video container. Set up sidecar captions in the same output group, but different output from your video. For more
 * information, see https://docs.aws.amazon.com/mediaconvert/latest/ug/scc-srt-output-captions.html. When you work
 * directly in your JSON job specification, include this object and any required children when you set destinationType
 * to SCC.
 */
final class SccDestinationSettings
{
    /**
     * Set Framerate (SccDestinationFramerate) to make sure that the captions and the video are synchronized in the output.
     * Specify a frame rate that matches the frame rate of the associated video. If the video frame rate is 29.97, choose
     * 29.97 dropframe (FRAMERATE_29_97_DROPFRAME) only if the video has video_insertion=true and drop_frame_timecode=true;
     * otherwise, choose 29.97 non-dropframe (FRAMERATE_29_97_NON_DROPFRAME).
     *
     * @var SccDestinationFramerate::*|null
     */
    private $framerate;

    /**
     * @param array{
     *   Framerate?: null|SccDestinationFramerate::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->framerate = $input['Framerate'] ?? null;
    }

    /**
     * @param array{
     *   Framerate?: null|SccDestinationFramerate::*,
     * }|SccDestinationSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return SccDestinationFramerate::*|null
     */
    public function getFramerate(): ?string
    {
        return $this->framerate;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->framerate) {
            if (!SccDestinationFramerate::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "framerate" for "%s". The value "%s" is not a valid "SccDestinationFramerate".', __CLASS__, $v));
            }
            $payload['framerate'] = $v;
        }

        return $payload;
    }
}
