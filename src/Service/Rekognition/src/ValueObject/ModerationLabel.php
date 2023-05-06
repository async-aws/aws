<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Provides information about a single type of inappropriate, unwanted, or offensive content found in an image or video.
 * Each type of moderated content has a label within a hierarchical taxonomy. For more information, see Content
 * moderation in the Amazon Rekognition Developer Guide.
 */
final class ModerationLabel
{
    /**
     * Specifies the confidence that Amazon Rekognition has that the label has been correctly identified.
     */
    private $confidence;

    /**
     * The label name for the type of unsafe content detected in the image.
     */
    private $name;

    /**
     * The name for the parent label. Labels at the top level of the hierarchy have the parent label `""`.
     */
    private $parentName;

    /**
     * @param array{
     *   Confidence?: null|float,
     *   Name?: null|string,
     *   ParentName?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->confidence = $input['Confidence'] ?? null;
        $this->name = $input['Name'] ?? null;
        $this->parentName = $input['ParentName'] ?? null;
    }

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

    public function getParentName(): ?string
    {
        return $this->parentName;
    }
}
