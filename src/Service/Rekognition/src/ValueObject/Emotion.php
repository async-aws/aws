<?php

namespace AsyncAws\Rekognition\ValueObject;

use AsyncAws\Rekognition\Enum\EmotionName;

final class Emotion
{
    /**
     * Type of emotion detected.
     */
    private $Type;

    /**
     * Level of confidence in the determination.
     */
    private $Confidence;

    /**
     * @param array{
     *   Type?: null|EmotionName::*,
     *   Confidence?: null|float,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Type = $input['Type'] ?? null;
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
     * @return EmotionName::*|null
     */
    public function getType(): ?string
    {
        return $this->Type;
    }
}
