<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Lambda\Enum\TracingMode;

/**
 * The function's X-Ray tracing configuration.
 */
final class TracingConfigResponse
{
    /**
     * The tracing mode.
     *
     * @var TracingMode::*|string|null
     */
    private $mode;

    /**
     * @param array{
     *   Mode?: null|TracingMode::*|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->mode = $input['Mode'] ?? null;
    }

    /**
     * @param array{
     *   Mode?: null|TracingMode::*|string,
     * }|TracingConfigResponse $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return TracingMode::*|string|null
     */
    public function getMode(): ?string
    {
        return $this->mode;
    }
}
