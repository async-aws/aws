<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Type that describes the face Amazon Rekognition chose to compare with the faces in the target. This contains a
 * bounding box for the selected face and confidence level that the bounding box contains a face. Note that Amazon
 * Rekognition selects the largest face in the source image for this comparison.
 */
final class ComparedSourceImageFace
{
    /**
     * Bounding box of the face.
     *
     * @var BoundingBox|null
     */
    private $boundingBox;

    /**
     * Confidence level that the selected bounding box contains a face.
     *
     * @var float|null
     */
    private $confidence;

    /**
     * @param array{
     *   BoundingBox?: BoundingBox|array|null,
     *   Confidence?: float|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->boundingBox = isset($input['BoundingBox']) ? BoundingBox::create($input['BoundingBox']) : null;
        $this->confidence = $input['Confidence'] ?? null;
    }

    /**
     * @param array{
     *   BoundingBox?: BoundingBox|array|null,
     *   Confidence?: float|null,
     * }|ComparedSourceImageFace $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBoundingBox(): ?BoundingBox
    {
        return $this->boundingBox;
    }

    public function getConfidence(): ?float
    {
        return $this->confidence;
    }
}
