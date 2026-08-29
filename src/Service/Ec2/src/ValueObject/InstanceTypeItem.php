<?php

namespace AsyncAws\Ec2\ValueObject;

/**
 * An instance type name or wildcard pattern in an instance type specification.
 */
final class InstanceTypeItem
{
    /**
     * The instance type or wildcard pattern (for example, `t3.*` or `m5.large`).
     *
     * @var string|null
     */
    private $instanceType;

    /**
     * @param array{
     *   InstanceType?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->instanceType = $input['InstanceType'] ?? null;
    }

    /**
     * @param array{
     *   InstanceType?: string|null,
     * }|InstanceTypeItem $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getInstanceType(): ?string
    {
        return $this->instanceType;
    }
}
