<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\BandwidthReductionFilterSharpening;
use AsyncAws\MediaConvert\Enum\BandwidthReductionFilterStrength;

/**
 * The Bandwidth reduction filter increases the video quality of your output relative to its bitrate. Use to lower the
 * bitrate of your constant quality QVBR output, with little or no perceptual decrease in quality. Or, use to increase
 * the video quality of outputs with other rate control modes relative to the bitrate that you specify. Bandwidth
 * reduction increases further when your input is low quality or noisy. Outputs that use this feature incur pro-tier
 * pricing. When you include Bandwidth reduction filter, you cannot include the Noise reducer preprocessor.
 */
final class BandwidthReductionFilter
{
    /**
     * Optionally specify the level of sharpening to apply when you use the Bandwidth reduction filter. Sharpening adds
     * contrast to the edges of your video content and can reduce softness. Keep the default value Off to apply no
     * sharpening. Set Sharpening strength to Low to apply a minimal amount of sharpening, or High to apply a maximum amount
     * of sharpening.
     *
     * @var BandwidthReductionFilterSharpening::*|null
     */
    private $sharpening;

    /**
     * Specify the strength of the Bandwidth reduction filter. For most workflows, we recommend that you choose Auto to
     * reduce the bandwidth of your output with little to no perceptual decrease in video quality. For high quality and high
     * bitrate outputs, choose Low. For the most bandwidth reduction, choose High. We recommend that you choose High for low
     * bitrate outputs. Note that High may incur a slight increase in the softness of your output.
     *
     * @var BandwidthReductionFilterStrength::*|null
     */
    private $strength;

    /**
     * @param array{
     *   Sharpening?: BandwidthReductionFilterSharpening::*|null,
     *   Strength?: BandwidthReductionFilterStrength::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->sharpening = $input['Sharpening'] ?? null;
        $this->strength = $input['Strength'] ?? null;
    }

    /**
     * @param array{
     *   Sharpening?: BandwidthReductionFilterSharpening::*|null,
     *   Strength?: BandwidthReductionFilterStrength::*|null,
     * }|BandwidthReductionFilter $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return BandwidthReductionFilterSharpening::*|null
     */
    public function getSharpening(): ?string
    {
        return $this->sharpening;
    }

    /**
     * @return BandwidthReductionFilterStrength::*|null
     */
    public function getStrength(): ?string
    {
        return $this->strength;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->sharpening) {
            if (!BandwidthReductionFilterSharpening::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "sharpening" for "%s". The value "%s" is not a valid "BandwidthReductionFilterSharpening".', __CLASS__, $v));
            }
            $payload['sharpening'] = $v;
        }
        if (null !== $v = $this->strength) {
            if (!BandwidthReductionFilterStrength::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "strength" for "%s". The value "%s" is not a valid "BandwidthReductionFilterStrength".', __CLASS__, $v));
            }
            $payload['strength'] = $v;
        }

        return $payload;
    }
}
