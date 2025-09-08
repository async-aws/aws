<?php

namespace AsyncAws\Rekognition\ValueObject;

use AsyncAws\Rekognition\Enum\GenderType;

/**
 * The predicted gender of a detected face.
 *
 * Amazon Rekognition makes gender binary (male/female) predictions based on the physical appearance of a face in a
 * particular image. This kind of prediction is not designed to categorize a person’s gender identity, and you
 * shouldn't use Amazon Rekognition to make such a determination. For example, a male actor wearing a long-haired wig
 * and earrings for a role might be predicted as female.
 *
 * Using Amazon Rekognition to make gender binary predictions is best suited for use cases where aggregate gender
 * distribution statistics need to be analyzed without identifying specific users. For example, the percentage of female
 * users compared to male users on a social media platform.
 *
 * We don't recommend using gender binary predictions to make decisions that impact an individual's rights, privacy, or
 * access to services.
 */
final class Gender
{
    /**
     * The predicted gender of the face.
     *
     * @var GenderType::*|null
     */
    private $value;

    /**
     * Level of confidence in the prediction.
     *
     * @var float|null
     */
    private $confidence;

    /**
     * @param array{
     *   Value?: GenderType::*|null,
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
     *   Value?: GenderType::*|null,
     *   Confidence?: float|null,
     * }|Gender $input
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
     * @return GenderType::*|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }
}
