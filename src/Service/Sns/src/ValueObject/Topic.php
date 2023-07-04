<?php

namespace AsyncAws\Sns\ValueObject;

/**
 * A wrapper type for the topic's Amazon Resource Name (ARN). To retrieve a topic's attributes, use
 * `GetTopicAttributes`.
 */
final class Topic
{
    /**
     * The topic's ARN.
     *
     * @var string|null
     */
    private $topicArn;

    /**
     * @param array{
     *   TopicArn?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->topicArn = $input['TopicArn'] ?? null;
    }

    /**
     * @param array{
     *   TopicArn?: null|string,
     * }|Topic $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getTopicArn(): ?string
    {
        return $this->topicArn;
    }
}
