<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * The details of the guardrail image coverage.
 */
final class GuardrailImageCoverage
{
    /**
     * The count (integer) of images guardrails guarded.
     *
     * @var int|null
     */
    private $guarded;

    /**
     * Represents the total number of images (integer) that were in the request (guarded and unguarded).
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
     * }|GuardrailImageCoverage $input
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
