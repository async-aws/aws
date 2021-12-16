<?php

namespace AsyncAws\Sns\ValueObject;

/**
 * Encloses data related to a successful message in a batch request for topic.
 */
final class PublishBatchResultEntry
{
    /**
     * The `Id` of an entry in a batch request.
     */
    private $id;

    /**
     * An identifier for the message.
     */
    private $messageId;

    /**
     * This parameter applies only to FIFO (first-in-first-out) topics.
     */
    private $sequenceNumber;

    /**
     * @param array{
     *   Id?: null|string,
     *   MessageId?: null|string,
     *   SequenceNumber?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? null;
        $this->messageId = $input['MessageId'] ?? null;
        $this->sequenceNumber = $input['SequenceNumber'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getMessageId(): ?string
    {
        return $this->messageId;
    }

    public function getSequenceNumber(): ?string
    {
        return $this->sequenceNumber;
    }
}
