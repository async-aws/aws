<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains data processing unit (DPU) configuration settings and parameter mappings for a notebook engine.
 */
final class EngineConfiguration
{
    /**
     * The number of DPUs to use for the coordinator. A coordinator is a special executor that orchestrates processing work
     * and manages other executors in a notebook session. The default is 1.
     */
    private $coordinatorDpuSize;

    /**
     * The maximum number of DPUs that can run concurrently.
     */
    private $maxConcurrentDpus;

    /**
     * The default number of DPUs to use for executors. An executor is the smallest unit of compute that a notebook session
     * can request from Athena. The default is 1.
     */
    private $defaultExecutorDpuSize;

    /**
     * Contains additional notebook engine `MAP<string, string>` parameter mappings in the form of key-value pairs. To
     * specify an Athena notebook that the Jupyter server will download and serve, specify a value for the
     * StartSessionRequest$NotebookVersion field, and then add a key named `NotebookId` to `AdditionalConfigs` that has the
     * value of the Athena notebook ID.
     */
    private $additionalConfigs;

    /**
     * Specifies custom jar files and Spark properties for use cases like cluster encryption, table formats, and general
     * Spark tuning.
     */
    private $sparkProperties;

    /**
     * @param array{
     *   CoordinatorDpuSize?: null|int,
     *   MaxConcurrentDpus: int,
     *   DefaultExecutorDpuSize?: null|int,
     *   AdditionalConfigs?: null|array<string, string>,
     *   SparkProperties?: null|array<string, string>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->coordinatorDpuSize = $input['CoordinatorDpuSize'] ?? null;
        $this->maxConcurrentDpus = $input['MaxConcurrentDpus'] ?? $this->throwException(new InvalidArgument('Missing required field "MaxConcurrentDpus".'));
        $this->defaultExecutorDpuSize = $input['DefaultExecutorDpuSize'] ?? null;
        $this->additionalConfigs = $input['AdditionalConfigs'] ?? null;
        $this->sparkProperties = $input['SparkProperties'] ?? null;
    }

    /**
     * @param array{
     *   CoordinatorDpuSize?: null|int,
     *   MaxConcurrentDpus: int,
     *   DefaultExecutorDpuSize?: null|int,
     *   AdditionalConfigs?: null|array<string, string>,
     *   SparkProperties?: null|array<string, string>,
     * }|EngineConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, string>
     */
    public function getAdditionalConfigs(): array
    {
        return $this->additionalConfigs ?? [];
    }

    public function getCoordinatorDpuSize(): ?int
    {
        return $this->coordinatorDpuSize;
    }

    public function getDefaultExecutorDpuSize(): ?int
    {
        return $this->defaultExecutorDpuSize;
    }

    public function getMaxConcurrentDpus(): int
    {
        return $this->maxConcurrentDpus;
    }

    /**
     * @return array<string, string>
     */
    public function getSparkProperties(): array
    {
        return $this->sparkProperties ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->coordinatorDpuSize) {
            $payload['CoordinatorDpuSize'] = $v;
        }
        if (null === $v = $this->maxConcurrentDpus) {
            throw new InvalidArgument(sprintf('Missing parameter "MaxConcurrentDpus" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['MaxConcurrentDpus'] = $v;
        if (null !== $v = $this->defaultExecutorDpuSize) {
            $payload['DefaultExecutorDpuSize'] = $v;
        }
        if (null !== $v = $this->additionalConfigs) {
            if (empty($v)) {
                $payload['AdditionalConfigs'] = new \stdClass();
            } else {
                $payload['AdditionalConfigs'] = [];
                foreach ($v as $name => $mv) {
                    $payload['AdditionalConfigs'][$name] = $mv;
                }
            }
        }
        if (null !== $v = $this->sparkProperties) {
            if (empty($v)) {
                $payload['SparkProperties'] = new \stdClass();
            } else {
                $payload['SparkProperties'] = [];
                foreach ($v as $name => $mv) {
                    $payload['SparkProperties'][$name] = $mv;
                }
            }
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
