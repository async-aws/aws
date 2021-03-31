<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Lambda\Enum\TracingMode;

/**
 * The function's AWS X-Ray tracing configuration.
 */
final class TracingConfigResponse
{
    /**
     * The tracing mode.
     */
    private $mode;

    /**
     * @param array{
     *   Mode?: null|TracingMode::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->mode = $input['Mode'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return TracingMode::*|null
     */
    public function getMode(): ?string
    {
        return $this->mode;
    }
}
