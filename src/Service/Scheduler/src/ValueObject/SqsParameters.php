<?php

namespace AsyncAws\Scheduler\ValueObject;

/**
 * The templated target type for the Amazon SQS `SendMessage` [^1] API operation. Contains the message group ID to use
 * when the target is a FIFO queue. If you specify an Amazon SQS FIFO queue as a target, the queue must have
 * content-based deduplication enabled. For more information, see Using the Amazon SQS message deduplication ID [^2] in
 * the *Amazon SQS Developer Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_SendMessage.html
 * [^2]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/using-messagededuplicationid-property.html
 */
final class SqsParameters
{
    /**
     * The FIFO message group ID to use as the target.
     *
     * @var string|null
     */
    private $messageGroupId;

    /**
     * @param array{
     *   MessageGroupId?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->messageGroupId = $input['MessageGroupId'] ?? null;
    }

    /**
     * @param array{
     *   MessageGroupId?: string|null,
     * }|SqsParameters $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMessageGroupId(): ?string
    {
        return $this->messageGroupId;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->messageGroupId) {
            $payload['MessageGroupId'] = $v;
        }

        return $payload;
    }
}
