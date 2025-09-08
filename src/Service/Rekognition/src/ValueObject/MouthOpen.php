<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Indicates whether or not the mouth on the face is open, and the confidence level in the determination.
 */
final class MouthOpen
{
    /**
     * Boolean value that indicates whether the mouth on the face is open or not.
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
     * }|MouthOpen $input
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
