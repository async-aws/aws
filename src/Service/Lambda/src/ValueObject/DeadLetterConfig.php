<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * The function's dead letter queue.
 */
final class DeadLetterConfig
{
    /**
     * The Amazon Resource Name (ARN) of an Amazon SQS queue or Amazon SNS topic.
     */
    private $targetArn;

    /**
     * @param array{
     *   TargetArn?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->targetArn = $input['TargetArn'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getTargetArn(): ?string
    {
        return $this->targetArn;
    }
}
