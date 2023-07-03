<?php

namespace AsyncAws\Sns\ValueObject;

/**
 * A wrapper type for the attributes of an Amazon SNS subscription.
 */
final class Subscription
{
    /**
     * The subscription's ARN.
     *
     * @var string|null
     */
    private $subscriptionArn;

    /**
     * The subscription's owner.
     *
     * @var string|null
     */
    private $owner;

    /**
     * The subscription's protocol.
     *
     * @var string|null
     */
    private $protocol;

    /**
     * The subscription's endpoint (format depends on the protocol).
     *
     * @var string|null
     */
    private $endpoint;

    /**
     * The ARN of the subscription's topic.
     *
     * @var string|null
     */
    private $topicArn;

    /**
     * @param array{
     *   SubscriptionArn?: null|string,
     *   Owner?: null|string,
     *   Protocol?: null|string,
     *   Endpoint?: null|string,
     *   TopicArn?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->subscriptionArn = $input['SubscriptionArn'] ?? null;
        $this->owner = $input['Owner'] ?? null;
        $this->protocol = $input['Protocol'] ?? null;
        $this->endpoint = $input['Endpoint'] ?? null;
        $this->topicArn = $input['TopicArn'] ?? null;
    }

    /**
     * @param array{
     *   SubscriptionArn?: null|string,
     *   Owner?: null|string,
     *   Protocol?: null|string,
     *   Endpoint?: null|string,
     *   TopicArn?: null|string,
     * }|Subscription $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function getProtocol(): ?string
    {
        return $this->protocol;
    }

    public function getSubscriptionArn(): ?string
    {
        return $this->subscriptionArn;
    }

    public function getTopicArn(): ?string
    {
        return $this->topicArn;
    }
}
