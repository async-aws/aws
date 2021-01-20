<?php

namespace AsyncAws\Rekognition\ValueObject;

use AsyncAws\Rekognition\Enum\GenderType;

/**
 * The predicted gender of a detected face.
 */
final class Gender
{
    /**
     * The predicted gender of the face.
     */
    private $value;

    /**
     * Level of confidence in the prediction.
     */
    private $confidence;

    /**
     * @param array{
     *   Value?: null|GenderType::*,
     *   Confidence?: null|float,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->value = $input['Value'] ?? null;
        $this->confidence = $input['Confidence'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getConfidence(): ?float
    {
        return $this->confidence;
    }

    /**
     * @return GenderType::*|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }
}
