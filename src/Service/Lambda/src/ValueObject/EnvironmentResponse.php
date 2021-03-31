<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * The function's environment variables.
 */
final class EnvironmentResponse
{
    /**
     * Environment variable key-value pairs.
     */
    private $variables;

    /**
     * Error messages for environment variables that couldn't be applied.
     */
    private $error;

    /**
     * @param array{
     *   Variables?: null|array<string, string>,
     *   Error?: null|EnvironmentError|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->variables = $input['Variables'] ?? null;
        $this->error = isset($input['Error']) ? EnvironmentError::create($input['Error']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getError(): ?EnvironmentError
    {
        return $this->error;
    }

    /**
     * @return array<string, string>
     */
    public function getVariables(): array
    {
        return $this->variables ?? [];
    }
}
