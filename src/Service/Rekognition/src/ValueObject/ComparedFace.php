<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Provides information about the celebrity's face, such as its location on the image.
 */
final class ComparedFace
{
    /**
     * Bounding box of the face.
     */
    private $boundingBox;

    /**
     * Level of confidence that what the bounding box contains is a face.
     */
    private $confidence;

    /**
     * An array of facial landmarks.
     */
    private $landmarks;

    /**
     * Indicates the pose of the face as determined by its pitch, roll, and yaw.
     */
    private $pose;

    /**
     * Identifies face image brightness and sharpness.
     */
    private $quality;

    /**
     * @param array{
     *   BoundingBox?: null|BoundingBox|array,
     *   Confidence?: null|float,
     *   Landmarks?: null|Landmark[],
     *   Pose?: null|Pose|array,
     *   Quality?: null|ImageQuality|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->boundingBox = isset($input['BoundingBox']) ? BoundingBox::create($input['BoundingBox']) : null;
        $this->confidence = $input['Confidence'] ?? null;
        $this->landmarks = isset($input['Landmarks']) ? array_map([Landmark::class, 'create'], $input['Landmarks']) : null;
        $this->pose = isset($input['Pose']) ? Pose::create($input['Pose']) : null;
        $this->quality = isset($input['Quality']) ? ImageQuality::create($input['Quality']) : null;
    }

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

    /**
     * @return Landmark[]
     */
    public function getLandmarks(): array
    {
        return $this->landmarks ?? [];
    }

    public function getPose(): ?Pose
    {
        return $this->pose;
    }

    public function getQuality(): ?ImageQuality
    {
        return $this->quality;
    }
}
