<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * For motion overlays that don't have a built-in frame rate, specify the frame rate of the overlay in frames per
 * second, as a fraction. For example, specify 24 fps as 24/1. The overlay frame rate doesn't need to match the frame
 * rate of the underlying video.
 */
final class MotionImageInsertionFramerate
{
    /**
     * The bottom of the fraction that expresses your overlay frame rate. For example, if your frame rate is 24 fps, set
     * this value to 1.
     *
     * @var int|null
     */
    private $framerateDenominator;

    /**
     * The top of the fraction that expresses your overlay frame rate. For example, if your frame rate is 24 fps, set this
     * value to 24.
     *
     * @var int|null
     */
    private $framerateNumerator;

    /**
     * @param array{
     *   FramerateDenominator?: int|null,
     *   FramerateNumerator?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->framerateDenominator = $input['FramerateDenominator'] ?? null;
        $this->framerateNumerator = $input['FramerateNumerator'] ?? null;
    }

    /**
     * @param array{
     *   FramerateDenominator?: int|null,
     *   FramerateNumerator?: int|null,
     * }|MotionImageInsertionFramerate $input
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
