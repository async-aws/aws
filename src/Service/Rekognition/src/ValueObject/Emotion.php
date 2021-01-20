<?php

namespace AsyncAws\Rekognition\ValueObject;

use AsyncAws\Rekognition\Enum\EmotionName;

/**
 * The emotions that appear to be expressed on the face, and the confidence level in the determination. The API is only
 * making a determination of the physical appearance of a person's face. It is not a determination of the person’s
 * internal emotional state and should not be used in such a way. For example, a person pretending to have a sad face
 * might not be sad emotionally.
 */
final class Emotion
{
    /**
     * Type of emotion detected.
     */
    private $type;

    /**
     * Level of confidence in the determination.
     */
    private $confidence;

    /**
     * @param array{
     *   Type?: null|EmotionName::*,
     *   Confidence?: null|float,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->type = $input['Type'] ?? null;
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
     * @return EmotionName::*|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }
}
