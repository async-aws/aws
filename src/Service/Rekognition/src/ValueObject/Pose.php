<?php

namespace AsyncAws\Rekognition\ValueObject;

final class Pose
{
    /**
     * Value representing the face rotation on the roll axis.
     */
    private $Roll;

    /**
     * Value representing the face rotation on the yaw axis.
     */
    private $Yaw;

    /**
     * Value representing the face rotation on the pitch axis.
     */
    private $Pitch;

    /**
     * @param array{
     *   Roll?: null|float,
     *   Yaw?: null|float,
     *   Pitch?: null|float,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Roll = $input['Roll'] ?? null;
        $this->Yaw = $input['Yaw'] ?? null;
        $this->Pitch = $input['Pitch'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getPitch(): ?float
    {
        return $this->Pitch;
    }

    public function getRoll(): ?float
    {
        return $this->Roll;
    }

    public function getYaw(): ?float
    {
        return $this->Yaw;
    }
}
