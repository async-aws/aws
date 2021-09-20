<?php

namespace AsyncAws\AppSync\ValueObject;

/**
 * The `LambdaConflictHandlerConfig` when configuring LAMBDA as the Conflict Handler.
 */
final class LambdaConflictHandlerConfig
{
    /**
     * The Arn for the Lambda function to use as the Conflict Handler.
     */
    private $lambdaConflictHandlerArn;

    /**
     * @param array{
     *   lambdaConflictHandlerArn?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->lambdaConflictHandlerArn = $input['lambdaConflictHandlerArn'] ?? null;
    }

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
