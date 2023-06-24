<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Indicates the direction the eyes are gazing in (independent of the head pose) as determined by its pitch and yaw.
 */
final class EyeDirection
{
    /**
     * Value representing eye direction on the yaw axis.
     */
    private $yaw;

    /**
     * Value representing eye direction on the pitch axis.
     */
    private $pitch;

    /**
     * The confidence that the service has in its predicted eye direction.
     */
    private $confidence;

    /**
     * @param array{
     *   Yaw?: null|float,
     *   Pitch?: null|float,
     *   Confidence?: null|float,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->yaw = $input['Yaw'] ?? null;
        $this->pitch = $input['Pitch'] ?? null;
        $this->confidence = $input['Confidence'] ?? null;
    }

    /**
     * @param array{
     *   Yaw?: null|float,
     *   Pitch?: null|float,
     *   Confidence?: null|float,
     * }|EyeDirection $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getConfidence(): ?float
    {
        return $this->confidence;
    }

    public function getPitch(): ?float
    {
        return $this->pitch;
    }

    public function getYaw(): ?float
    {
        return $this->yaw;
    }
}
