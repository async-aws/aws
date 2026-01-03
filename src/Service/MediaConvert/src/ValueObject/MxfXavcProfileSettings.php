<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\MxfXavcDurationMode;

/**
 * Specify the XAVC profile settings for MXF outputs when you set your MXF profile to XAVC.
 */
final class MxfXavcProfileSettings
{
    /**
     * To create an output that complies with the XAVC file format guidelines for interoperability, keep the default value,
     * Drop frames for compliance. To include all frames from your input in this output, keep the default setting, Allow any
     * duration. The number of frames that MediaConvert excludes when you set this to Drop frames for compliance depends on
     * the output frame rate and duration.
     *
     * @var MxfXavcDurationMode::*|null
     */
    private $durationMode;

    /**
     * Specify a value for this setting only for outputs that you set up with one of these two XAVC profiles: XAVC HD Intra
     * CBG or XAVC 4K Intra CBG. Specify the amount of space in each frame that the service reserves for ancillary data,
     * such as teletext captions. The default value for this setting is 1492 bytes per frame. This should be sufficient to
     * prevent overflow unless you have multiple pages of teletext captions data. If you have a large amount of teletext
     * data, specify a larger number.
     *
     * @var int|null
     */
    private $maxAncDataSize;

    /**
     * @param array{
     *   DurationMode?: MxfXavcDurationMode::*|null,
     *   MaxAncDataSize?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->durationMode = $input['DurationMode'] ?? null;
        $this->maxAncDataSize = $input['MaxAncDataSize'] ?? null;
    }

    /**
     * @param array{
     *   DurationMode?: MxfXavcDurationMode::*|null,
     *   MaxAncDataSize?: int|null,
     * }|MxfXavcProfileSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return MxfXavcDurationMode::*|null
     */
    public function getDurationMode(): ?string
    {
        return $this->durationMode;
    }

    public function getMaxAncDataSize(): ?int
    {
        return $this->maxAncDataSize;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->durationMode) {
            if (!MxfXavcDurationMode::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "durationMode" for "%s". The value "%s" is not a valid "MxfXavcDurationMode".', __CLASS__, $v));
            }
            $payload['durationMode'] = $v;
        }
        if (null !== $v = $this->maxAncDataSize) {
            $payload['maxAncDataSize'] = $v;
        }

        return $payload;
    }
}
