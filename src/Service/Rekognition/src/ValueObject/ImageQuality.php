<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Identifies image brightness and sharpness. Default attribute.
 */
final class ImageQuality
{
    /**
     * Value representing brightness of the face. The service returns a value between 0 and 100 (inclusive). A higher value
     * indicates a brighter face image.
     */
    private $Brightness;

    /**
     * Value representing sharpness of the face. The service returns a value between 0 and 100 (inclusive). A higher value
     * indicates a sharper face image.
     */
    private $Sharpness;

    /**
     * @param array{
     *   Brightness?: null|float,
     *   Sharpness?: null|float,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Brightness = $input['Brightness'] ?? null;
        $this->Sharpness = $input['Sharpness'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBrightness(): ?float
    {
        return $this->Brightness;
    }

    public function getSharpness(): ?float
    {
        return $this->Sharpness;
    }
}
