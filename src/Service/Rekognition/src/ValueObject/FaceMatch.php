<?php

namespace AsyncAws\Rekognition\ValueObject;

final class FaceMatch
{
    /**
     * Confidence in the match of this face with the input face.
     */
    private $Similarity;

    /**
     * Describes the face properties such as the bounding box, face ID, image ID of the source image, and external image ID
     * that you assigned.
     */
    private $Face;

    /**
     * @param array{
     *   Similarity?: null|float,
     *   Face?: null|Face|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Similarity = $input['Similarity'] ?? null;
        $this->Face = isset($input['Face']) ? Face::create($input['Face']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFace(): ?Face
    {
        return $this->Face;
    }

    public function getSimilarity(): ?float
    {
        return $this->Similarity;
    }
}
