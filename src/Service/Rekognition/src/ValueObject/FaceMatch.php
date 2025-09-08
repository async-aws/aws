<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Provides face metadata. In addition, it also provides the confidence in the match of this face with the input face.
 */
final class FaceMatch
{
    /**
     * Confidence in the match of this face with the input face.
     *
     * @var float|null
     */
    private $similarity;

    /**
     * Describes the face properties such as the bounding box, face ID, image ID of the source image, and external image ID
     * that you assigned.
     *
     * @var Face|null
     */
    private $face;

    /**
     * @param array{
     *   Similarity?: float|null,
     *   Face?: Face|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->similarity = $input['Similarity'] ?? null;
        $this->face = isset($input['Face']) ? Face::create($input['Face']) : null;
    }

    /**
     * @param array{
     *   Similarity?: float|null,
     *   Face?: Face|array|null,
     * }|FaceMatch $input
     */
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
