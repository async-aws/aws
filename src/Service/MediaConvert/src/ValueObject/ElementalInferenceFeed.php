<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\MediaConvert\Enum\ElementalInferenceFeedManagementState;

/**
 * Elemental Inference Feed.
 */
final class ElementalInferenceFeed
{
    /**
     * Feed ARN.
     *
     * @var string|null
     */
    private $arn;

    /**
     * Elemental Inference Feed management state.
     *
     * @var ElementalInferenceFeedManagementState::*|null
     */
    private $feedManagementState;

    /**
     * @param array{
     *   Arn?: string|null,
     *   FeedManagementState?: ElementalInferenceFeedManagementState::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arn = $input['Arn'] ?? null;
        $this->feedManagementState = $input['FeedManagementState'] ?? null;
    }

    /**
     * @param array{
     *   Arn?: string|null,
     *   FeedManagementState?: ElementalInferenceFeedManagementState::*|null,
     * }|ElementalInferenceFeed $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArn(): ?string
    {
        return $this->arn;
    }

    /**
     * @return ElementalInferenceFeedManagementState::*|null
     */
    public function getFeedManagementState(): ?string
    {
        return $this->feedManagementState;
    }
}
