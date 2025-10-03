<?php

namespace AsyncAws\Scheduler\ValueObject;

/**
 * An object that contains information about an Amazon SQS queue that EventBridge Scheduler uses as a dead-letter queue
 * for your schedule. If specified, EventBridge Scheduler delivers failed events that could not be successfully
 * delivered to a target to the queue.
 */
final class DeadLetterConfig
{
    /**
     * The Amazon Resource Name (ARN) of the SQS queue specified as the destination for the dead-letter queue.
     *
     * @var string|null
     */
    private $arn;

    /**
     * @param array{
     *   Arn?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arn = $input['Arn'] ?? null;
    }

    /**
     * @param array{
     *   Arn?: string|null,
     * }|DeadLetterConfig $input
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
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->arn) {
            $payload['Arn'] = $v;
        }

        return $payload;
    }
}
