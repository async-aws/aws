<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\MediaConvert\Enum\ElementalInferenceFeature;

/**
 * The Elemental Inference configuration used in this job.
 */
final class ElementalInferenceConfiguration
{
    /**
     * A list of Elemental Inference features used in this job.
     *
     * @var list<ElementalInferenceFeature::*>|null
     */
    private $features;

    /**
     * A list of Elemental Inference feeds used by this job.
     *
     * @var ElementalInferenceFeed[]|null
     */
    private $feeds;

    /**
     * @param array{
     *   Features?: array<ElementalInferenceFeature::*>|null,
     *   Feeds?: array<ElementalInferenceFeed|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->features = $input['Features'] ?? null;
        $this->feeds = isset($input['Feeds']) ? array_map([ElementalInferenceFeed::class, 'create'], $input['Feeds']) : null;
    }

    /**
     * @param array{
     *   Features?: array<ElementalInferenceFeature::*>|null,
     *   Feeds?: array<ElementalInferenceFeed|array>|null,
     * }|ElementalInferenceConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<ElementalInferenceFeature::*>
     */
    public function getFeatures(): array
    {
        return $this->features ?? [];
    }

    /**
     * @return ElementalInferenceFeed[]
     */
    public function getFeeds(): array
    {
        return $this->feeds ?? [];
    }
}
