<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Provides face metadata for target image faces that are analyzed by `CompareFaces` and `RecognizeCelebrities`.
 */
final class ComparedFace
{
    /**
     * Bounding box of the face.
     *
     * @var BoundingBox|null
     */
    private $boundingBox;

    /**
     * Level of confidence that what the bounding box contains is a face.
     *
     * @var float|null
     */
    private $confidence;

    /**
     * An array of facial landmarks.
     *
     * @var Landmark[]|null
     */
    private $landmarks;

    /**
     * Indicates the pose of the face as determined by its pitch, roll, and yaw.
     *
     * @var Pose|null
     */
    private $pose;

    /**
     * Identifies face image brightness and sharpness.
     *
     * @var ImageQuality|null
     */
    private $quality;

    /**
     * The emotions that appear to be expressed on the face, and the confidence level in the determination. Valid values
     * include "Happy", "Sad", "Angry", "Confused", "Disgusted", "Surprised", "Calm", "Unknown", and "Fear".
     *
     * @var Emotion[]|null
     */
    private $emotions;

    /**
     * Indicates whether or not the face is smiling, and the confidence level in the determination.
     *
     * @var Smile|null
     */
    private $smile;

    /**
     * @param array{
     *   BoundingBox?: BoundingBox|array|null,
     *   Confidence?: float|null,
     *   Landmarks?: array<Landmark|array>|null,
     *   Pose?: Pose|array|null,
     *   Quality?: ImageQuality|array|null,
     *   Emotions?: array<Emotion|array>|null,
     *   Smile?: Smile|array|null,
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

    /**
     * @param array{
     *   BoundingBox?: BoundingBox|array|null,
     *   Confidence?: float|null,
     *   Landmarks?: array<Landmark|array>|null,
     *   Pose?: Pose|array|null,
     *   Quality?: ImageQuality|array|null,
     *   Emotions?: array<Emotion|array>|null,
     *   Smile?: Smile|array|null,
     * }|ComparedFace $input
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
