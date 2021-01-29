<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Object containing both the face metadata (stored in the backend database), and facial attributes that are detected
 * but aren't stored in the database.
 */
final class FaceRecord
{
    /**
     * Describes the face properties such as the bounding box, face ID, image ID of the input image, and external image ID
     * that you assigned.
     */
    private $face;

    /**
     * Structure containing attributes of the face that the algorithm detected.
     */
    private $faceDetail;

    /**
     * @param array{
     *   Face?: null|Face|array,
     *   FaceDetail?: null|FaceDetail|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->face = isset($input['Face']) ? Face::create($input['Face']) : null;
        $this->faceDetail = isset($input['FaceDetail']) ? FaceDetail::create($input['FaceDetail']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFace(): ?Face
    {
        return $this->face;
    }

    public function getFaceDetail(): ?FaceDetail
    {
        return $this->faceDetail;
    }
}
