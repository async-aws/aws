<?php

namespace AsyncAws\Translate\ValueObject;

/**
 * The term being translated by the custom terminology.
 */
final class Term
{
    /**
     * The source text of the term being translated by the custom terminology.
     */
    private $sourceText;

    /**
     * The target text of the term being translated by the custom terminology.
     */
    private $targetText;

    /**
     * @param array{
     *   SourceText?: null|string,
     *   TargetText?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->sourceText = $input['SourceText'] ?? null;
        $this->targetText = $input['TargetText'] ?? null;
    }

    /**
     * @param array{
     *   SourceText?: null|string,
     *   TargetText?: null|string,
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
