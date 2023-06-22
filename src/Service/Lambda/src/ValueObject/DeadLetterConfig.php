<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * The dead-letter queue [^1] for failed asynchronous invocations.
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/invocation-async.html#dlq
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

    /**
     * @param array{
     *   TargetArn?: null|string,
     * }|DeadLetterConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getTargetArn(): ?string
    {
        return $this->targetArn;
    }
}
