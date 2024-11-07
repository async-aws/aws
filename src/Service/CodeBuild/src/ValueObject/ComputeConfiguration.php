<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\CodeBuild\Enum\MachineType;

/**
 * Contains compute attributes. These attributes only need be specified when your project's or fleet's `computeType` is
 * set to `ATTRIBUTE_BASED_COMPUTE`.
 */
final class ComputeConfiguration
{
    /**
     * The number of vCPUs of the instance type included in your fleet.
     *
     * @var int|null
     */
    private $vCpu;

    /**
     * The amount of memory of the instance type included in your fleet.
     *
     * @var int|null
     */
    private $memory;

    /**
     * The amount of disk space of the instance type included in your fleet.
     *
     * @var int|null
     */
    private $disk;

    /**
     * The machine type of the instance type included in your fleet.
     *
     * @var MachineType::*|null
     */
    private $machineType;

    /**
     * @param array{
     *   vCpu?: null|int,
     *   memory?: null|int,
     *   disk?: null|int,
     *   machineType?: null|MachineType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->vCpu = $input['vCpu'] ?? null;
        $this->memory = $input['memory'] ?? null;
        $this->disk = $input['disk'] ?? null;
        $this->machineType = $input['machineType'] ?? null;
    }

    /**
     * @param array{
     *   vCpu?: null|int,
     *   memory?: null|int,
     *   disk?: null|int,
     *   machineType?: null|MachineType::*,
     * }|ComputeConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDisk(): ?int
    {
        return $this->disk;
    }

    /**
     * @return MachineType::*|null
     */
    public function getMachineType(): ?string
    {
        return $this->machineType;
    }

    public function getMemory(): ?int
    {
        return $this->memory;
    }

    public function getVCpu(): ?int
    {
        return $this->vCpu;
    }
}
