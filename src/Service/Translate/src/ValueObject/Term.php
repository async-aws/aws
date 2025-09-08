<?php

namespace AsyncAws\Translate\ValueObject;

/**
 * The term being translated by the custom terminology.
 */
final class Term
{
    /**
     * The source text of the term being translated by the custom terminology.
     *
     * @var string|null
     */
    private $sourceText;

    /**
     * The target text of the term being translated by the custom terminology.
     *
     * @var string|null
     */
    private $targetText;

    /**
     * @param array{
     *   SourceText?: string|null,
     *   TargetText?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->sourceText = $input['SourceText'] ?? null;
        $this->targetText = $input['TargetText'] ?? null;
    }

    /**
     * @param array{
     *   SourceText?: string|null,
     *   TargetText?: string|null,
     * }|Term $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getSourceText(): ?string
    {
        return $this->sourceText;
    }

    public function getTargetText(): ?string
    {
        return $this->targetText;
    }
}
