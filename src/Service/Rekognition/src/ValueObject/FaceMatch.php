<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Provides face metadata. In addition, it also provides the confidence in the match of this face with the input face.
 */
final class FaceMatch
{
    /**
     * Confidence in the match of this face with the input face.
     */
    private $similarity;

    /**
     * Describes the face properties such as the bounding box, face ID, image ID of the source image, and external image ID
     * that you assigned.
     */
    private $face;

    /**
     * @param array{
     *   Similarity?: null|float,
     *   Face?: null|Face|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->similarity = $input['Similarity'] ?? null;
        $this->face = isset($input['Face']) ? Face::create($input['Face']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFace(): ?Face
    {
        return $this->face;
    }

    public function getSimilarity(): ?float
    {
        return $this->similarity;
    }
}
