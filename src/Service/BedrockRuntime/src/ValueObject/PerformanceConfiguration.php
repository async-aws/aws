<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\BedrockRuntime\Enum\PerformanceConfigLatency;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Performance settings for a model.
 */
final class PerformanceConfiguration
{
    /**
     * To use a latency-optimized version of the model, set to `optimized`.
     *
     * @var PerformanceConfigLatency::*|null
     */
    private $latency;

    /**
     * @param array{
     *   latency?: null|PerformanceConfigLatency::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->latency = $input['latency'] ?? null;
    }

    /**
     * @param array{
     *   latency?: null|PerformanceConfigLatency::*,
     * }|PerformanceConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return PerformanceConfigLatency::*|null
     */
    public function getLatency(): ?string
    {
        return $this->latency;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->latency) {
            if (!PerformanceConfigLatency::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "latency" for "%s". The value "%s" is not a valid "PerformanceConfigLatency".', __CLASS__, $v));
            }
            $payload['latency'] = $v;
        }

        return $payload;
    }
}
