<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * The action of the guardrail coverage details.
 */
final class GuardrailCoverage
{
    /**
     * The text characters of the guardrail coverage details.
     *
     * @var GuardrailTextCharactersCoverage|null
     */
    private $textCharacters;

    /**
     * The guardrail coverage for images (the number of images that guardrails guarded).
     *
     * @var GuardrailImageCoverage|null
     */
    private $images;

    /**
     * @param array{
     *   textCharacters?: null|GuardrailTextCharactersCoverage|array,
     *   images?: null|GuardrailImageCoverage|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->textCharacters = isset($input['textCharacters']) ? GuardrailTextCharactersCoverage::create($input['textCharacters']) : null;
        $this->images = isset($input['images']) ? GuardrailImageCoverage::create($input['images']) : null;
    }

    /**
     * @param array{
     *   textCharacters?: null|GuardrailTextCharactersCoverage|array,
     *   images?: null|GuardrailImageCoverage|array,
     * }|GuardrailCoverage $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getImages(): ?GuardrailImageCoverage
    {
        return $this->images;
    }

    public function getTextCharacters(): ?GuardrailTextCharactersCoverage
    {
        return $this->textCharacters;
    }
}
