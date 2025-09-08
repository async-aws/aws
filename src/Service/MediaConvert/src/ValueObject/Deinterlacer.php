<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\DeinterlaceAlgorithm;
use AsyncAws\MediaConvert\Enum\DeinterlacerControl;
use AsyncAws\MediaConvert\Enum\DeinterlacerMode;

/**
 * Settings for deinterlacer.
 */
final class Deinterlacer
{
    /**
     * Only applies when you set Deinterlace mode to Deinterlace or Adaptive. Interpolate produces sharper pictures, while
     * blend produces smoother motion. If your source file includes a ticker, such as a scrolling headline at the bottom of
     * the frame: Choose Interpolate ticker or Blend ticker. To apply field doubling: Choose Linear interpolation. Note that
     * Linear interpolation may introduce video artifacts into your output.
     *
     * @var DeinterlaceAlgorithm::*|null
     */
    private $algorithm;

    /**
     * - When set to NORMAL (default), the deinterlacer does not convert frames that are tagged in metadata as progressive.
     * It will only convert those that are tagged as some other type. - When set to FORCE_ALL_FRAMES, the deinterlacer
     * converts every frame to progressive - even those that are already tagged as progressive. Turn Force mode on only if
     * there is a good chance that the metadata has tagged frames as progressive when they are not progressive. Do not turn
     * on otherwise; processing frames that are already progressive into progressive will probably result in lower quality
     * video.
     *
     * @var DeinterlacerControl::*|null
     */
    private $control;

    /**
     * Use Deinterlacer to choose how the service will do deinterlacing. Default is Deinterlace.
     * - Deinterlace converts interlaced to progressive.
     * - Inverse telecine converts Hard Telecine 29.97i to progressive 23.976p.
     * - Adaptive auto-detects and converts to progressive.
     *
     * @var DeinterlacerMode::*|null
     */
    private $mode;

    /**
     * @param array{
     *   Algorithm?: DeinterlaceAlgorithm::*|null,
     *   Control?: DeinterlacerControl::*|null,
     *   Mode?: DeinterlacerMode::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->algorithm = $input['Algorithm'] ?? null;
        $this->control = $input['Control'] ?? null;
        $this->mode = $input['Mode'] ?? null;
    }

    /**
     * @param array{
     *   Algorithm?: DeinterlaceAlgorithm::*|null,
     *   Control?: DeinterlacerControl::*|null,
     *   Mode?: DeinterlacerMode::*|null,
     * }|Deinterlacer $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return DeinterlaceAlgorithm::*|null
     */
    public function getAlgorithm(): ?string
    {
        return $this->algorithm;
    }

    /**
     * @return DeinterlacerControl::*|null
     */
    public function getControl(): ?string
    {
        return $this->control;
    }

    /**
     * @return DeinterlacerMode::*|null
     */
    public function getMode(): ?string
    {
        return $this->mode;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->algorithm) {
            if (!DeinterlaceAlgorithm::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "algorithm" for "%s". The value "%s" is not a valid "DeinterlaceAlgorithm".', __CLASS__, $v));
            }
            $payload['algorithm'] = $v;
        }
        if (null !== $v = $this->control) {
            if (!DeinterlacerControl::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "control" for "%s". The value "%s" is not a valid "DeinterlacerControl".', __CLASS__, $v));
            }
            $payload['control'] = $v;
        }
        if (null !== $v = $this->mode) {
            if (!DeinterlacerMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "mode" for "%s". The value "%s" is not a valid "DeinterlacerMode".', __CLASS__, $v));
            }
            $payload['mode'] = $v;
        }

        return $payload;
    }
}
