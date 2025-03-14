<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * The guardrail coverage for the text characters.
 */
final class GuardrailTextCharactersCoverage
{
    /**
     * The text characters that were guarded by the guardrail coverage.
     *
     * @var int|null
     */
    private $guarded;

    /**
     * The total text characters by the guardrail coverage.
     *
     * @var int|null
     */
    private $total;

    /**
     * @param array{
     *   guarded?: null|int,
     *   total?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->guarded = $input['guarded'] ?? null;
        $this->total = $input['total'] ?? null;
    }

    /**
     * @param array{
     *   guarded?: null|int,
     *   total?: null|int,
     * }|GuardrailTextCharactersCoverage $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getGuarded(): ?int
    {
        return $this->guarded;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }
}
