<?php

namespace AsyncAws\AppSync\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Describes an Lambda data source configuration.
 */
final class LambdaDataSourceConfig
{
    /**
     * The Amazon Resource Name (ARN) for the Lambda function.
     *
     * @var string
     */
    private $lambdaFunctionArn;

    /**
     * @param array{
     *   lambdaFunctionArn: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->lambdaFunctionArn = $input['lambdaFunctionArn'] ?? $this->throwException(new InvalidArgument('Missing required field "lambdaFunctionArn".'));
    }

    /**
     * @param array{
     *   lambdaFunctionArn: string,
     * }|LambdaDataSourceConfig $input
     */
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
        $v = $this->lambdaFunctionArn;
        $payload['lambdaFunctionArn'] = $v;

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
