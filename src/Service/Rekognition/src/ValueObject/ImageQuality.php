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
    private $brightness;

    /**
     * Value representing sharpness of the face. The service returns a value between 0 and 100 (inclusive). A higher value
     * indicates a sharper face image.
     */
    private $sharpness;

    /**
     * @param array{
     *   Brightness?: null|float,
     *   Sharpness?: null|float,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->brightness = $input['Brightness'] ?? null;
        $this->sharpness = $input['Sharpness'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBrightness(): ?float
    {
        return $this->brightness;
    }

    public function getSharpness(): ?float
    {
        return $this->sharpness;
    }
}
