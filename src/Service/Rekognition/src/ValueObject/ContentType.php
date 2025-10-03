<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Contains information regarding the confidence and name of a detected content type.
 */
final class ContentType
{
    /**
     * The confidence level of the label given.
     *
     * @var float|null
     */
    private $confidence;

    /**
     * The name of the label.
     *
     * @var string|null
     */
    private $name;

    /**
     * @param array{
     *   Confidence?: float|null,
     *   Name?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->confidence = $input['Confidence'] ?? null;
        $this->name = $input['Name'] ?? null;
    }

    /**
     * @param array{
     *   Confidence?: float|null,
     *   Name?: string|null,
     * }|ContentType $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getConfidence(): ?float
    {
        return $this->confidence;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
