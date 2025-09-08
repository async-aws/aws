<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Specifies whether previous query results are reused, and if so, their maximum age.
 */
final class ResultReuseByAgeConfiguration
{
    /**
     * True if previous query results can be reused when the query is run; otherwise, false. The default is false.
     *
     * @var bool
     */
    private $enabled;

    /**
     * Specifies, in minutes, the maximum age of a previous query result that Athena should consider for reuse. The default
     * is 60.
     *
     * @var int|null
     */
    private $maxAgeInMinutes;

    /**
     * @param array{
     *   Enabled: bool,
     *   MaxAgeInMinutes?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->enabled = $input['Enabled'] ?? $this->throwException(new InvalidArgument('Missing required field "Enabled".'));
        $this->maxAgeInMinutes = $input['MaxAgeInMinutes'] ?? null;
    }

    /**
     * @param array{
     *   Enabled: bool,
     *   MaxAgeInMinutes?: int|null,
     * }|ResultReuseByAgeConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function getMaxAgeInMinutes(): ?int
    {
        return $this->maxAgeInMinutes;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->enabled;
        $payload['Enabled'] = (bool) $v;
        if (null !== $v = $this->maxAgeInMinutes) {
            $payload['MaxAgeInMinutes'] = $v;
        }

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
