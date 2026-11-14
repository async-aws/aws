<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The customer-managed runtime configuration for your function. Specifies the customer-managed runtime and the runtime
 * alias that your function uses.
 */
final class FunctionRuntimeConfig
{
    /**
     * The Amazon Resource Name (ARN) of the customer-managed runtime that your function uses.
     *
     * @var string
     */
    private $runtimeArn;

    /**
     * The name of the customer-managed runtime alias that your function uses. A runtime alias is a name that points to a
     * runtime version.
     *
     * @var string
     */
    private $runtimeAlias;

    /**
     * @param array{
     *   RuntimeArn: string,
     *   RuntimeAlias: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->runtimeArn = $input['RuntimeArn'] ?? $this->throwException(new InvalidArgument('Missing required field "RuntimeArn".'));
        $this->runtimeAlias = $input['RuntimeAlias'] ?? $this->throwException(new InvalidArgument('Missing required field "RuntimeAlias".'));
    }

    /**
     * @param array{
     *   RuntimeArn: string,
     *   RuntimeAlias: string,
     * }|FunctionRuntimeConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getRuntimeAlias(): string
    {
        return $this->runtimeAlias;
    }

    public function getRuntimeArn(): string
    {
        return $this->runtimeArn;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->runtimeArn;
        $payload['RuntimeArn'] = $v;
        $v = $this->runtimeAlias;
        $payload['RuntimeAlias'] = $v;

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
