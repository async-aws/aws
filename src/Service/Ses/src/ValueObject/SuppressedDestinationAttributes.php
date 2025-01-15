<?php

namespace AsyncAws\Ses\ValueObject;

/**
 * An object that contains additional attributes that are related an email address that is on the suppression list for
 * your account.
 */
final class SuppressedDestinationAttributes
{
    /**
     * The unique identifier of the email message that caused the email address to be added to the suppression list for your
     * account.
     *
     * @var string|null
     */
    private $messageId;

    /**
     * A unique identifier that's generated when an email address is added to the suppression list for your account.
     *
     * @var string|null
     */
    private $feedbackId;

    /**
     * @param array{
     *   MessageId?: null|string,
     *   FeedbackId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->messageId = $input['MessageId'] ?? null;
        $this->feedbackId = $input['FeedbackId'] ?? null;
    }

    /**
     * @param array{
     *   MessageId?: null|string,
     *   FeedbackId?: null|string,
     * }|SuppressedDestinationAttributes $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFeedbackId(): ?string
    {
        return $this->feedbackId;
    }

    public function getMessageId(): ?string
    {
        return $this->messageId;
    }
}
