<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Identifies the Amazon EC2 Auto Scaling group that Image Builder takes the build instance from, and how long Image
 * Builder waits when the group has no instance available.
 */
final class AutoScalingGroupConfiguration
{
    /**
     * The name of the Auto Scaling group, in the same account and Amazon Web Services Region as the infrastructure
     * configuration. To specify a group, you must have permissions to describe it.
     *
     * @var string
     */
    private $name;

    /**
     * The number of seconds that Image Builder waits for an instance to become available in the group before the build
     * fails. If you set this value to 0 or omit it, the build fails as soon as the group has no instance available.
     *
     * @var int|null
     */
    private $capacityWaitTimeoutSeconds;

    /**
     * @param array{
     *   name: string,
     *   capacityWaitTimeoutSeconds?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['name'] ?? $this->throwException(new InvalidArgument('Missing required field "name".'));
        $this->capacityWaitTimeoutSeconds = $input['capacityWaitTimeoutSeconds'] ?? null;
    }

    /**
     * @param array{
     *   name: string,
     *   capacityWaitTimeoutSeconds?: int|null,
     * }|AutoScalingGroupConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCapacityWaitTimeoutSeconds(): ?int
    {
        return $this->capacityWaitTimeoutSeconds;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
