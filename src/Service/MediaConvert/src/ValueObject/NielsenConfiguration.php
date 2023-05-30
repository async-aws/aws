<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Settings for your Nielsen configuration. If you don't do Nielsen measurement and analytics, ignore these settings.
 * When you enable Nielsen configuration (nielsenConfiguration), MediaConvert enables PCM to ID3 tagging for all outputs
 * in the job. To enable Nielsen configuration programmatically, include an instance of nielsenConfiguration in your
 * JSON job specification. Even if you don't include any children of nielsenConfiguration, you still enable the setting.
 */
final class NielsenConfiguration
{
    /**
     * Nielsen has discontinued the use of breakout code functionality. If you must include this property, set the value to
     * zero.
     */
    private $breakoutCode;

    /**
     * Use Distributor ID (DistributorID) to specify the distributor ID that is assigned to your organization by Neilsen.
     */
    private $distributorId;

    /**
     * @param array{
     *   BreakoutCode?: null|int,
     *   DistributorId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->breakoutCode = $input['BreakoutCode'] ?? null;
        $this->distributorId = $input['DistributorId'] ?? null;
    }

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
