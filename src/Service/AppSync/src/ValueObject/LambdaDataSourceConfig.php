<?php

namespace AsyncAws\AppSync\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The new Lambda configuration.
 */
final class LambdaDataSourceConfig
{
    /**
     * The Amazon Resource Name (ARN) for the Lambda function.
     */
    private $lambdaFunctionArn;

    /**
     * @param array{
     *   lambdaFunctionArn: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->lambdaFunctionArn = $input['lambdaFunctionArn'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getLambdaFunctionArn(): string
    {
        return $this->lambdaFunctionArn;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->lambdaFunctionArn) {
            throw new InvalidArgument(sprintf('Missing parameter "lambdaFunctionArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['lambdaFunctionArn'] = $v;

        return $payload;
    }
}
