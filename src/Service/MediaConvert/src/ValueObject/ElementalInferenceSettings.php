<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * The Elemental Inference settings used in this job.
 */
final class ElementalInferenceSettings
{
    /**
     * Optional. The source of your Elemental Inference Feed configuration. MediaConvert will reference this configuration
     * source when you specify job settings which use Elemental Inference.
     *
     * @var ElementalInferenceFeedConfigurationSource|null
     */
    private $feedConfigurationSource;

    /**
     * @param array{
     *   FeedConfigurationSource?: ElementalInferenceFeedConfigurationSource|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->feedConfigurationSource = isset($input['FeedConfigurationSource']) ? ElementalInferenceFeedConfigurationSource::create($input['FeedConfigurationSource']) : null;
    }

    /**
     * @param array{
     *   FeedConfigurationSource?: ElementalInferenceFeedConfigurationSource|array|null,
     * }|ElementalInferenceSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFeedConfigurationSource(): ?ElementalInferenceFeedConfigurationSource
    {
        return $this->feedConfigurationSource;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->feedConfigurationSource) {
            $payload['feedConfigurationSource'] = $v->requestBody();
        }

        return $payload;
    }
}
