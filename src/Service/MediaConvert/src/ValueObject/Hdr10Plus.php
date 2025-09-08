<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Setting for HDR10+ metadata insertion.
 */
final class Hdr10Plus
{
    /**
     * Specify the HDR10+ mastering display normalized peak luminance, in nits. This is the normalized actual peak luminance
     * of the mastering display, as defined by ST 2094-40.
     *
     * @var int|null
     */
    private $masteringMonitorNits;

    /**
     * Specify the HDR10+ target display nominal peak luminance, in nits. This is the nominal maximum luminance of the
     * target display as defined by ST 2094-40.
     *
     * @var int|null
     */
    private $targetMonitorNits;

    /**
     * @param array{
     *   MasteringMonitorNits?: int|null,
     *   TargetMonitorNits?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->masteringMonitorNits = $input['MasteringMonitorNits'] ?? null;
        $this->targetMonitorNits = $input['TargetMonitorNits'] ?? null;
    }

    /**
     * @param array{
     *   MasteringMonitorNits?: int|null,
     *   TargetMonitorNits?: int|null,
     * }|Hdr10Plus $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMasteringMonitorNits(): ?int
    {
        return $this->masteringMonitorNits;
    }

    public function getTargetMonitorNits(): ?int
    {
        return $this->targetMonitorNits;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->masteringMonitorNits) {
            $payload['masteringMonitorNits'] = $v;
        }
        if (null !== $v = $this->targetMonitorNits) {
            $payload['targetMonitorNits'] = $v;
        }

        return $payload;
    }
}
