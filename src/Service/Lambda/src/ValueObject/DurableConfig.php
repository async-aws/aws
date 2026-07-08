<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * Configuration settings for durable functions [^1], including execution timeout, retention period for execution
 * history, and an optional ARN of the Key Management Service (KMS) customer managed key that is used to encrypt your
 * durable execution's payload data, including input, output, and error payloads.
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/durable-functions.html
 */
final class DurableConfig
{
    /**
     * The ARN of the Key Management Service (KMS) customer managed key that is used to encrypt your durable execution's
     * payload data, including input, output, and error payloads.
     *
     * @var string|null
     */
    private $kmsKeyArn;

    /**
     * The number of days to retain execution history after a durable execution completes. After this period, execution
     * history is no longer available through the GetDurableExecutionHistory API.
     *
     * @var int|null
     */
    private $retentionPeriodInDays;

    /**
     * The maximum time (in seconds) that a durable execution can run before timing out. This timeout applies to the entire
     * durable execution, not individual function invocations.
     *
     * @var int|null
     */
    private $executionTimeout;

    /**
     * @param array{
     *   KMSKeyArn?: string|null,
     *   RetentionPeriodInDays?: int|null,
     *   ExecutionTimeout?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->kmsKeyArn = $input['KMSKeyArn'] ?? null;
        $this->retentionPeriodInDays = $input['RetentionPeriodInDays'] ?? null;
        $this->executionTimeout = $input['ExecutionTimeout'] ?? null;
    }

    /**
     * @param array{
     *   KMSKeyArn?: string|null,
     *   RetentionPeriodInDays?: int|null,
     *   ExecutionTimeout?: int|null,
     * }|DurableConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getExecutionTimeout(): ?int
    {
        return $this->executionTimeout;
    }

    public function getKmsKeyArn(): ?string
    {
        return $this->kmsKeyArn;
    }

    public function getRetentionPeriodInDays(): ?int
    {
        return $this->retentionPeriodInDays;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->kmsKeyArn) {
            $payload['KMSKeyArn'] = $v;
        }
        if (null !== $v = $this->retentionPeriodInDays) {
            $payload['RetentionPeriodInDays'] = $v;
        }
        if (null !== $v = $this->executionTimeout) {
            $payload['ExecutionTimeout'] = $v;
        }

        return $payload;
    }
}
