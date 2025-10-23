<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * The dead-letter queue [^1] for failed asynchronous invocations.
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/invocation-async-retain-records.html#invocation-dlq
 */
final class DeadLetterConfig
{
    /**
     * The Amazon Resource Name (ARN) of an Amazon SQS queue or Amazon SNS topic.
     *
     * @var string|null
     */
    private $targetArn;

    /**
     * @param array{
     *   TargetArn?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->targetArn = $input['TargetArn'] ?? null;
    }

    /**
     * @param array{
     *   TargetArn?: string|null,
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

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->targetArn) {
            $payload['TargetArn'] = $v;
        }

        return $payload;
    }
}
