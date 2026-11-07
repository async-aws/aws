<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * The source of your Elemental Inference Feed configuration.
 */
final class ElementalInferenceFeedConfigurationSource
{
    /**
     * The ARN of an Elemental Inference Feed.
     *
     * @var string|null
     */
    private $feedArn;

    /**
     * @param array{
     *   FeedArn?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->feedArn = $input['FeedArn'] ?? null;
    }

    /**
     * @param array{
     *   FeedArn?: string|null,
     * }|ElementalInferenceFeedConfigurationSource $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFeedArn(): ?string
    {
        return $this->feedArn;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->feedArn) {
            $payload['feedArn'] = $v;
        }

        return $payload;
    }
}
