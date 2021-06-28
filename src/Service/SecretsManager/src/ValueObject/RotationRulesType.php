<?php

namespace AsyncAws\SecretsManager\ValueObject;

/**
 * A structure that defines the rotation configuration for the secret.
 */
final class RotationRulesType
{
    /**
     * Specifies the number of days between automatic scheduled rotations of the secret.
     */
    private $automaticallyAfterDays;

    /**
     * @param array{
     *   AutomaticallyAfterDays?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->automaticallyAfterDays = $input['AutomaticallyAfterDays'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAutomaticallyAfterDays(): ?string
    {
        return $this->automaticallyAfterDays;
    }
}
