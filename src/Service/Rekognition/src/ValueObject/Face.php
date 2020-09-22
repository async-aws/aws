<?php

namespace AsyncAws\Rekognition\ValueObject;

final class Face
{
    /**
     * Unique identifier that Amazon Rekognition assigns to the face.
     */
    private $FaceId;

    /**
     * Bounding box of the face.
     */
    private $BoundingBox;

    /**
     * Unique identifier that Amazon Rekognition assigns to the input image.
     */
    private $ImageId;

    /**
     * Identifier that you assign to all the faces in the input image.
     */
    private $ExternalImageId;

    /**
     * Confidence level that the bounding box contains a face (and not a different object such as a tree).
     */
    private $Confidence;

    /**
     * @param array{
     *   FaceId?: null|string,
     *   BoundingBox?: null|BoundingBox|array,
     *   ImageId?: null|string,
     *   ExternalImageId?: null|string,
     *   Confidence?: null|float,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->FaceId = $input['FaceId'] ?? null;
        $this->BoundingBox = isset($input['BoundingBox']) ? BoundingBox::create($input['BoundingBox']) : null;
        $this->ImageId = $input['ImageId'] ?? null;
        $this->ExternalImageId = $input['ExternalImageId'] ?? null;
        $this->Confidence = $input['Confidence'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBoundingBox(): ?BoundingBox
    {
        return $this->BoundingBox;
    }

    public function getConfidence(): ?float
    {
        return $this->Confidence;
    }

    public function getExternalImageId(): ?string
    {
        return $this->ExternalImageId;
    }

    public function getFaceId(): ?string
    {
        return $this->FaceId;
    }

    public function getImageId(): ?string
    {
        return $this->ImageId;
    }
}
