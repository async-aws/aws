<?php

namespace AsyncAws\Ses\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class ListManagementOptions
{
    /**
     * The name of the contact list.
     */
    private $ContactListName;

    /**
     * The name of the topic.
     */
    private $TopicName;

    /**
     * @param array{
     *   ContactListName: string,
     *   TopicName?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->ContactListName = $input['ContactListName'] ?? null;
        $this->TopicName = $input['TopicName'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getContactListName(): string
    {
        return $this->ContactListName;
    }

    public function getTopicName(): ?string
    {
        return $this->TopicName;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->ContactListName) {
            throw new InvalidArgument(sprintf('Missing parameter "ContactListName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ContactListName'] = $v;
        if (null !== $v = $this->TopicName) {
            $payload['TopicName'] = $v;
        }

        return $payload;
    }
}
