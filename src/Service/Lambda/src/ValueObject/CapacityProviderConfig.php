<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Configuration for the capacity provider that manages compute resources for Lambda functions.
 */
final class CapacityProviderConfig
{
    /**
     * Configuration for Lambda-managed instances used by the capacity provider.
     *
     * @var LambdaManagedInstancesCapacityProviderConfig
     */
    private $lambdaManagedInstancesCapacityProviderConfig;

    /**
     * @param array{
     *   LambdaManagedInstancesCapacityProviderConfig: LambdaManagedInstancesCapacityProviderConfig|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->lambdaManagedInstancesCapacityProviderConfig = isset($input['LambdaManagedInstancesCapacityProviderConfig']) ? LambdaManagedInstancesCapacityProviderConfig::create($input['LambdaManagedInstancesCapacityProviderConfig']) : $this->throwException(new InvalidArgument('Missing required field "LambdaManagedInstancesCapacityProviderConfig".'));
    }

    /**
     * @param array{
     *   LambdaManagedInstancesCapacityProviderConfig: LambdaManagedInstancesCapacityProviderConfig|array,
     * }|CapacityProviderConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getLambdaManagedInstancesCapacityProviderConfig(): LambdaManagedInstancesCapacityProviderConfig
    {
        return $this->lambdaManagedInstancesCapacityProviderConfig;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->lambdaManagedInstancesCapacityProviderConfig;
        $payload['LambdaManagedInstancesCapacityProviderConfig'] = $v->requestBody();

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
