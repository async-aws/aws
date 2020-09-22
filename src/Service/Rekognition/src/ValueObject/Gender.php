<?php

namespace AsyncAws\Rekognition\ValueObject;

use AsyncAws\Rekognition\Enum\GenderType;

final class Gender
{
    /**
     * The predicted gender of the face.
     */
    private $Value;

    /**
     * Level of confidence in the prediction.
     */
    private $Confidence;

    /**
     * @param array{
     *   Value?: null|GenderType::*,
     *   Confidence?: null|float,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Value = $input['Value'] ?? null;
        $this->Confidence = $input['Confidence'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getConfidence(): ?float
    {
        return $this->Confidence;
    }

    /**
     * @return GenderType::*|null
     */
    public function getValue(): ?string
    {
        return $this->Value;
    }
}
