<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Ignore this setting unless your input captions format is SCC. To have the service compensate for differing frame
 * rates between your input captions and input video, specify the frame rate of the captions file. Specify this value as
 * a fraction. When you work directly in your JSON job specification, use the settings framerateNumerator and
 * framerateDenominator. For example, you might specify 24 / 1 for 24 fps, 25 / 1 for 25 fps, 24000 / 1001 for 23.976
 * fps, or 30000 / 1001 for 29.97 fps.
 */
final class CaptionSourceFramerate
{
    /**
     * Specify the denominator of the fraction that represents the frame rate for the setting Caption source frame rate
     * (CaptionSourceFramerate). Use this setting along with the setting Framerate numerator (framerateNumerator).
     */
    private $framerateDenominator;

    /**
     * Specify the numerator of the fraction that represents the frame rate for the setting Caption source frame rate
     * (CaptionSourceFramerate). Use this setting along with the setting Framerate denominator (framerateDenominator).
     */
    private $framerateNumerator;

    /**
     * @param array{
     *   FramerateDenominator?: null|int,
     *   FramerateNumerator?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->framerateDenominator = $input['FramerateDenominator'] ?? null;
        $this->framerateNumerator = $input['FramerateNumerator'] ?? null;
    }

    /**
     * @param array{
     *   FramerateDenominator?: null|int,
     *   FramerateNumerator?: null|int,
     * }|CaptionSourceFramerate $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFramerateDenominator(): ?int
    {
        return $this->framerateDenominator;
    }

    public function getFramerateNumerator(): ?int
    {
        return $this->framerateNumerator;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->framerateDenominator) {
            $payload['framerateDenominator'] = $v;
        }
        if (null !== $v = $this->framerateNumerator) {
            $payload['framerateNumerator'] = $v;
        }

        return $payload;
    }
}
