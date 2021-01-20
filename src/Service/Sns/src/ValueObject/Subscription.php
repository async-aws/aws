<?php

namespace AsyncAws\Sns\ValueObject;

/**
 * A wrapper type for the attributes of an Amazon SNS subscription.
 */
final class Subscription
{
    /**
     * The subscription's ARN.
     */
    private $SubscriptionArn;

    /**
     * The subscription's owner.
     */
    private $Owner;

    /**
     * The subscription's protocol.
     */
    private $Protocol;

    /**
     * The subscription's endpoint (format depends on the protocol).
     */
    private $Endpoint;

    /**
     * The ARN of the subscription's topic.
     */
    private $TopicArn;

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
        $this->SubscriptionArn = $input['SubscriptionArn'] ?? null;
        $this->Owner = $input['Owner'] ?? null;
        $this->Protocol = $input['Protocol'] ?? null;
        $this->Endpoint = $input['Endpoint'] ?? null;
        $this->TopicArn = $input['TopicArn'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEndpoint(): ?string
    {
        return $this->Endpoint;
    }

    public function getOwner(): ?string
    {
        return $this->Owner;
    }

    public function getProtocol(): ?string
    {
        return $this->Protocol;
    }

    public function getSubscriptionArn(): ?string
    {
        return $this->SubscriptionArn;
    }

    public function getTopicArn(): ?string
    {
        return $this->TopicArn;
    }
}
