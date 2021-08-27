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
     * The emotions that appear to be expressed on the face, and the confidence level in the determination. Valid values
     * include "Happy", "Sad", "Angry", "Confused", "Disgusted", "Surprised", "Calm", "Unknown", and "Fear".
     */
    private $emotions;

    /**
     * Indicates whether or not the face is smiling, and the confidence level in the determination.
     */
    private $smile;

    /**
     * @param array{
     *   BoundingBox?: null|BoundingBox|array,
     *   Confidence?: null|float,
     *   Landmarks?: null|Landmark[],
     *   Pose?: null|Pose|array,
     *   Quality?: null|ImageQuality|array,
     *   Emotions?: null|Emotion[],
     *   Smile?: null|Smile|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->boundingBox = isset($input['BoundingBox']) ? BoundingBox::create($input['BoundingBox']) : null;
        $this->confidence = $input['Confidence'] ?? null;
        $this->landmarks = isset($input['Landmarks']) ? array_map([Landmark::class, 'create'], $input['Landmarks']) : null;
        $this->pose = isset($input['Pose']) ? Pose::create($input['Pose']) : null;
        $this->quality = isset($input['Quality']) ? ImageQuality::create($input['Quality']) : null;
        $this->emotions = isset($input['Emotions']) ? array_map([Emotion::class, 'create'], $input['Emotions']) : null;
        $this->smile = isset($input['Smile']) ? Smile::create($input['Smile']) : null;
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
     * @return Emotion[]
     */
    public function getEmotions(): array
    {
        return $this->emotions ?? [];
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

    public function getSmile(): ?Smile
    {
        return $this->smile;
    }
}
