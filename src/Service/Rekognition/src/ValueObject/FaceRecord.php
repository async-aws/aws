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
     *
     * @var Face|null
     */
    private $face;

    /**
     * Structure containing attributes of the face that the algorithm detected.
     *
     * @var FaceDetail|null
     */
    private $faceDetail;

    /**
     * @param array{
     *   Face?: Face|array|null,
     *   FaceDetail?: FaceDetail|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->face = isset($input['Face']) ? Face::create($input['Face']) : null;
        $this->faceDetail = isset($input['FaceDetail']) ? FaceDetail::create($input['FaceDetail']) : null;
    }

    /**
     * @param array{
     *   Face?: Face|array|null,
     *   FaceDetail?: FaceDetail|array|null,
     * }|FaceRecord $input
     */
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
