<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Indicates the pose of the face as determined by its pitch, roll, and yaw.
 */
final class Pose
{
    /**
     * Value representing the face rotation on the roll axis.
     *
     * @var float|null
     */
    private $roll;

    /**
     * Value representing the face rotation on the yaw axis.
     *
     * @var float|null
     */
    private $yaw;

    /**
     * Value representing the face rotation on the pitch axis.
     *
     * @var float|null
     */
    private $pitch;

    /**
     * @param array{
     *   Roll?: float|null,
     *   Yaw?: float|null,
     *   Pitch?: float|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->roll = $input['Roll'] ?? null;
        $this->yaw = $input['Yaw'] ?? null;
        $this->pitch = $input['Pitch'] ?? null;
    }

    /**
     * @param array{
     *   Roll?: float|null,
     *   Yaw?: float|null,
     *   Pitch?: float|null,
     * }|Pose $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getPitch(): ?float
    {
        return $this->pitch;
    }

    public function getRoll(): ?float
    {
        return $this->roll;
    }

    public function getYaw(): ?float
    {
        return $this->yaw;
    }
}
