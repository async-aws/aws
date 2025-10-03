<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * `FaceOccluded` should return "true" with a high confidence score if a detected face’s eyes, nose, and mouth are
 * partially captured or if they are covered by masks, dark sunglasses, cell phones, hands, or other objects.
 * `FaceOccluded` should return "false" with a high confidence score if common occurrences that do not impact face
 * verification are detected, such as eye glasses, lightly tinted sunglasses, strands of hair, and others.
 *
 * You can use `FaceOccluded` to determine if an obstruction on a face negatively impacts using the image for face
 * matching.
 */
final class FaceOccluded
{
    /**
     * True if a detected face’s eyes, nose, and mouth are partially captured or if they are covered by masks, dark
     * sunglasses, cell phones, hands, or other objects. False if common occurrences that do not impact face verification
     * are detected, such as eye glasses, lightly tinted sunglasses, strands of hair, and others.
     *
     * @var bool|null
     */
    private $value;

    /**
     * The confidence that the service has detected the presence of a face occlusion.
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
     * }|FaceOccluded $input
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
