<?php

namespace AsyncAws\Rekognition\ValueObject;

use AsyncAws\Rekognition\Enum\EmotionName;

/**
 * The API returns a prediction of an emotion based on a person's facial expressions, along with the confidence level
 * for the predicted emotion. It is not a determination of the person’s internal emotional state and should not be
 * used in such a way. For example, a person pretending to have a sad face might not be sad emotionally. The API is not
 * intended to be used, and you may not use it, in a manner that violates the EU Artificial Intelligence Act or any
 * other applicable law.
 */
final class Emotion
{
    /**
     * Type of emotion detected.
     *
     * @var EmotionName::*|string|null
     */
    private $type;

    /**
     * Level of confidence in the determination.
     *
     * @var float|null
     */
    private $confidence;

    /**
     * @param array{
     *   Type?: null|EmotionName::*|string,
     *   Confidence?: null|float,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->type = $input['Type'] ?? null;
        $this->confidence = $input['Confidence'] ?? null;
    }

    /**
     * @param array{
     *   Type?: null|EmotionName::*|string,
     *   Confidence?: null|float,
     * }|Emotion $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getConfidence(): ?float
    {
        return $this->confidence;
    }

    /**
     * @return EmotionName::*|string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }
}
