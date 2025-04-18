<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Metrics for a call to Converse [^1].
 *
 * [^1]: https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_Converse.html
 */
final class ConverseMetrics
{
    /**
     * The latency of the call to `Converse`, in milliseconds.
     *
     * @var int
     */
    private $latencyMs;

    /**
     * @param array{
     *   latencyMs: int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->latencyMs = $input['latencyMs'] ?? $this->throwException(new InvalidArgument('Missing required field "latencyMs".'));
    }

    /**
     * @param array{
     *   latencyMs: int,
     * }|ConverseMetrics $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getLatencyMs(): int
    {
        return $this->latencyMs;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
