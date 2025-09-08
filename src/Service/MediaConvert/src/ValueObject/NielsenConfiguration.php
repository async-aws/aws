<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Settings for your Nielsen configuration. If you don't do Nielsen measurement and analytics, ignore these settings.
 * When you enable Nielsen configuration, MediaConvert enables PCM to ID3 tagging for all outputs in the job.
 */
final class NielsenConfiguration
{
    /**
     * Nielsen has discontinued the use of breakout code functionality. If you must include this property, set the value to
     * zero.
     *
     * @var int|null
     */
    private $breakoutCode;

    /**
     * Use Distributor ID to specify the distributor ID that is assigned to your organization by Nielsen.
     *
     * @var string|null
     */
    private $distributorId;

    /**
     * @param array{
     *   BreakoutCode?: int|null,
     *   DistributorId?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->breakoutCode = $input['BreakoutCode'] ?? null;
        $this->distributorId = $input['DistributorId'] ?? null;
    }

    /**
     * @param array{
     *   BreakoutCode?: int|null,
     *   DistributorId?: string|null,
     * }|NielsenConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBreakoutCode(): ?int
    {
        return $this->breakoutCode;
    }

    public function getDistributorId(): ?string
    {
        return $this->distributorId;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->breakoutCode) {
            $payload['breakoutCode'] = $v;
        }
        if (null !== $v = $this->distributorId) {
            $payload['distributorId'] = $v;
        }

        return $payload;
    }
}
