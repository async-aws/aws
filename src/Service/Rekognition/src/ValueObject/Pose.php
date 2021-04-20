<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Indicates the pose of the face as determined by its pitch, roll, and yaw. Default attribute.
 */
final class Pose
{
    /**
     * Value representing the face rotation on the roll axis.
     */
    private $roll;

    /**
     * Value representing the face rotation on the yaw axis.
     */
    private $yaw;

    /**
     * Value representing the face rotation on the pitch axis.
     */
    private $pitch;

    /**
     * @param array{
     *   Roll?: null|float,
     *   Yaw?: null|float,
     *   Pitch?: null|float,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->roll = $input['Roll'] ?? null;
        $this->yaw = $input['Yaw'] ?? null;
        $this->pitch = $input['Pitch'] ?? null;
    }

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
