<?php

namespace AsyncAws\Rekognition\ValueObject;

final class FaceRecord
{
    /**
     * Describes the face properties such as the bounding box, face ID, image ID of the input image, and external image ID
     * that you assigned.
     */
    private $Face;

    /**
     * Structure containing attributes of the face that the algorithm detected.
     */
    private $FaceDetail;

    /**
     * @param array{
     *   Face?: null|\AsyncAws\Rekognition\ValueObject\Face|array,
     *   FaceDetail?: null|\AsyncAws\Rekognition\ValueObject\FaceDetail|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Face = isset($input['Face']) ? Face::create($input['Face']) : null;
        $this->FaceDetail = isset($input['FaceDetail']) ? FaceDetail::create($input['FaceDetail']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFace(): ?Face
    {
        return $this->Face;
    }

    public function getFaceDetail(): ?FaceDetail
    {
        return $this->FaceDetail;
    }
}
