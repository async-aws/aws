<?php

namespace AsyncAws\CodeBuild\ValueObject;

/**
 * Describes a network interface.
 */
final class NetworkInterface
{
    /**
     * The ID of the subnet.
     */
    private $subnetId;

    /**
     * The ID of the network interface.
     */
    private $networkInterfaceId;

    /**
     * @param array{
     *   subnetId?: null|string,
     *   networkInterfaceId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->subnetId = $input['subnetId'] ?? null;
        $this->networkInterfaceId = $input['networkInterfaceId'] ?? null;
    }

    /**
     * @param array{
     *   subnetId?: null|string,
     *   networkInterfaceId?: null|string,
     * }|NetworkInterface $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getNetworkInterfaceId(): ?string
    {
        return $this->networkInterfaceId;
    }

    public function getSubnetId(): ?string
    {
        return $this->subnetId;
    }
}
