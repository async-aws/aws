<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Describes the face properties such as the bounding box, face ID, image ID of the input image, and external image ID
 * that you assigned.
 */
final class Face
{
    /**
     * Unique identifier that Amazon Rekognition assigns to the face.
     *
     * @var string|null
     */
    private $faceId;

    /**
     * Bounding box of the face.
     *
     * @var BoundingBox|null
     */
    private $boundingBox;

    /**
     * Unique identifier that Amazon Rekognition assigns to the input image.
     *
     * @var string|null
     */
    private $imageId;

    /**
     * Identifier that you assign to all the faces in the input image.
     *
     * @var string|null
     */
    private $externalImageId;

    /**
     * Confidence level that the bounding box contains a face (and not a different object such as a tree).
     *
     * @var float|null
     */
    private $confidence;

    /**
     * The version of the face detect and storage model that was used when indexing the face vector.
     *
     * @var string|null
     */
    private $indexFacesModelVersion;

    /**
     * Unique identifier assigned to the user.
     *
     * @var string|null
     */
    private $userId;

    /**
     * @param array{
     *   FaceId?: string|null,
     *   BoundingBox?: BoundingBox|array|null,
     *   ImageId?: string|null,
     *   ExternalImageId?: string|null,
     *   Confidence?: float|null,
     *   IndexFacesModelVersion?: string|null,
     *   UserId?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->faceId = $input['FaceId'] ?? null;
        $this->boundingBox = isset($input['BoundingBox']) ? BoundingBox::create($input['BoundingBox']) : null;
        $this->imageId = $input['ImageId'] ?? null;
        $this->externalImageId = $input['ExternalImageId'] ?? null;
        $this->confidence = $input['Confidence'] ?? null;
        $this->indexFacesModelVersion = $input['IndexFacesModelVersion'] ?? null;
        $this->userId = $input['UserId'] ?? null;
    }

    /**
     * @param array{
     *   FaceId?: string|null,
     *   BoundingBox?: BoundingBox|array|null,
     *   ImageId?: string|null,
     *   ExternalImageId?: string|null,
     *   Confidence?: float|null,
     *   IndexFacesModelVersion?: string|null,
     *   UserId?: string|null,
     * }|Face $input
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

    public function getExternalImageId(): ?string
    {
        return $this->externalImageId;
    }

    public function getFaceId(): ?string
    {
        return $this->faceId;
    }

    public function getImageId(): ?string
    {
        return $this->imageId;
    }

    public function getIndexFacesModelVersion(): ?string
    {
        return $this->indexFacesModelVersion;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }
}
