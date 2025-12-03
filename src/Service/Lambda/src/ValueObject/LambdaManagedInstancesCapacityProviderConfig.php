<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Configuration for Lambda-managed instances used by the capacity provider.
 */
final class LambdaManagedInstancesCapacityProviderConfig
{
    /**
     * The Amazon Resource Name (ARN) of the capacity provider.
     *
     * @var string
     */
    private $capacityProviderArn;

    /**
     * The maximum number of concurrent execution environments that can run on each compute instance.
     *
     * @var int|null
     */
    private $perExecutionEnvironmentMaxConcurrency;

    /**
     * The amount of memory in GiB allocated per vCPU for execution environments.
     *
     * @var float|null
     */
    private $executionEnvironmentMemoryGibPerVcpu;

    /**
     * @param array{
     *   CapacityProviderArn: string,
     *   PerExecutionEnvironmentMaxConcurrency?: int|null,
     *   ExecutionEnvironmentMemoryGiBPerVCpu?: float|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->capacityProviderArn = $input['CapacityProviderArn'] ?? $this->throwException(new InvalidArgument('Missing required field "CapacityProviderArn".'));
        $this->perExecutionEnvironmentMaxConcurrency = $input['PerExecutionEnvironmentMaxConcurrency'] ?? null;
        $this->executionEnvironmentMemoryGibPerVcpu = $input['ExecutionEnvironmentMemoryGiBPerVCpu'] ?? null;
    }

    /**
     * @param array{
     *   CapacityProviderArn: string,
     *   PerExecutionEnvironmentMaxConcurrency?: int|null,
     *   ExecutionEnvironmentMemoryGiBPerVCpu?: float|null,
     * }|LambdaManagedInstancesCapacityProviderConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCapacityProviderArn(): string
    {
        return $this->capacityProviderArn;
    }

    public function getExecutionEnvironmentMemoryGibPerVcpu(): ?float
    {
        return $this->executionEnvironmentMemoryGibPerVcpu;
    }

    public function getPerExecutionEnvironmentMaxConcurrency(): ?int
    {
        return $this->perExecutionEnvironmentMaxConcurrency;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->capacityProviderArn;
        $payload['CapacityProviderArn'] = $v;
        if (null !== $v = $this->perExecutionEnvironmentMaxConcurrency) {
            $payload['PerExecutionEnvironmentMaxConcurrency'] = $v;
        }
        if (null !== $v = $this->executionEnvironmentMemoryGibPerVcpu) {
            $payload['ExecutionEnvironmentMemoryGiBPerVCpu'] = $v;
        }

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
