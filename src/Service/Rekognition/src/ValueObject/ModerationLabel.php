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
     *
     * If you don't specify the `MinConfidence` parameter in the call to `DetectModerationLabels`, the operation returns
     * labels with a confidence value greater than or equal to 50 percent.
     *
     * @var float|null
     */
    private $confidence;

    /**
     * The label name for the type of unsafe content detected in the image.
     *
     * @var string|null
     */
    private $name;

    /**
     * The name for the parent label. Labels at the top level of the hierarchy have the parent label `""`.
     *
     * @var string|null
     */
    private $parentName;

    /**
     * The level of the moderation label with regard to its taxonomy, from 1 to 3.
     *
     * @var int|null
     */
    private $taxonomyLevel;

    /**
     * @param array{
     *   Confidence?: float|null,
     *   Name?: string|null,
     *   ParentName?: string|null,
     *   TaxonomyLevel?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->confidence = $input['Confidence'] ?? null;
        $this->name = $input['Name'] ?? null;
        $this->parentName = $input['ParentName'] ?? null;
        $this->taxonomyLevel = $input['TaxonomyLevel'] ?? null;
    }

    /**
     * @param array{
     *   Confidence?: float|null,
     *   Name?: string|null,
     *   ParentName?: string|null,
     *   TaxonomyLevel?: int|null,
     * }|ModerationLabel $input
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

    public function getParentName(): ?string
    {
        return $this->parentName;
    }

    public function getTaxonomyLevel(): ?int
    {
        return $this->taxonomyLevel;
    }
}
