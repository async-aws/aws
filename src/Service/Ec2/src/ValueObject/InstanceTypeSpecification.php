<?php

namespace AsyncAws\Ec2\ValueObject;

/**
 * Describes the instance type compatibility rules for an AMI, including lists of supported and unsupported instance
 * type patterns.
 */
final class InstanceTypeSpecification
{
    /**
     * The instance types that the AMI supports.
     *
     * @var InstanceTypeItem[]|null
     */
    private $supportedInstanceTypes;

    /**
     * The instance types that the AMI does not support.
     *
     * @var InstanceTypeItem[]|null
     */
    private $unsupportedInstanceTypes;

    /**
     * @param array{
     *   SupportedInstanceTypes?: array<InstanceTypeItem|array>|null,
     *   UnsupportedInstanceTypes?: array<InstanceTypeItem|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->supportedInstanceTypes = isset($input['SupportedInstanceTypes']) ? array_map([InstanceTypeItem::class, 'create'], $input['SupportedInstanceTypes']) : null;
        $this->unsupportedInstanceTypes = isset($input['UnsupportedInstanceTypes']) ? array_map([InstanceTypeItem::class, 'create'], $input['UnsupportedInstanceTypes']) : null;
    }

    /**
     * @param array{
     *   SupportedInstanceTypes?: array<InstanceTypeItem|array>|null,
     *   UnsupportedInstanceTypes?: array<InstanceTypeItem|array>|null,
     * }|InstanceTypeSpecification $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return InstanceTypeItem[]
     */
    public function getSupportedInstanceTypes(): array
    {
        return $this->supportedInstanceTypes ?? [];
    }

    /**
     * @return InstanceTypeItem[]
     */
    public function getUnsupportedInstanceTypes(): array
    {
        return $this->unsupportedInstanceTypes ?? [];
    }
}
