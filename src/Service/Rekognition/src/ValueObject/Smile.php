<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Indicates whether or not the face is smiling, and the confidence level in the determination.
 */
final class Smile
{
    /**
     * Boolean value that indicates whether the face is smiling or not.
     *
     * @var bool|null
     */
    private $value;

    /**
     * Level of confidence in the determination.
     *
     * @var float|null
     */
    private $confidence;

    /**
     * @param array{
     *   Value?: bool|null,
     *   Confidence?: float|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->value = $input['Value'] ?? null;
        $this->confidence = $input['Confidence'] ?? null;
    }

    /**
     * @param array{
     *   Value?: bool|null,
     *   Confidence?: float|null,
     * }|Smile $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getConfidence(): ?float
    {
        return $this->confidence;
    }

    public function getValue(): ?bool
    {
        return $this->value;
    }
}
