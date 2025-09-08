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
     * @var TracingMode::*|null
     */
    private $mode;

    /**
     * @param array{
     *   Mode?: TracingMode::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->mode = $input['Mode'] ?? null;
    }

    /**
     * @param array{
     *   Mode?: TracingMode::*|null,
     * }|TracingConfigResponse $input
     */
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
