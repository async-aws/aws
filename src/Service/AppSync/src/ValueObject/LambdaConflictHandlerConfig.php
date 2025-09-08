<?php

namespace AsyncAws\AppSync\ValueObject;

/**
 * The `LambdaConflictHandlerConfig` object when configuring `LAMBDA` as the Conflict Handler.
 */
final class LambdaConflictHandlerConfig
{
    /**
     * The Amazon Resource Name (ARN) for the Lambda function to use as the Conflict Handler.
     *
     * @var string|null
     */
    private $lambdaConflictHandlerArn;

    /**
     * @param array{
     *   lambdaConflictHandlerArn?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->lambdaConflictHandlerArn = $input['lambdaConflictHandlerArn'] ?? null;
    }

    /**
     * @param array{
     *   lambdaConflictHandlerArn?: string|null,
     * }|LambdaConflictHandlerConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getLambdaConflictHandlerArn(): ?string
    {
        return $this->lambdaConflictHandlerArn;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->lambdaConflictHandlerArn) {
            $payload['lambdaConflictHandlerArn'] = $v;
        }

        return $payload;
    }
}
