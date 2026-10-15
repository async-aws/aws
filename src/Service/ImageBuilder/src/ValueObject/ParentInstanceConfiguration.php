<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Settings for building on an instance that is already running, instead of an instance that Image Builder launches for
 * the build.
 */
final class ParentInstanceConfiguration
{
    /**
     * The Amazon EC2 Auto Scaling group that Image Builder takes the build instance from. This is required when you specify
     * `parentInstance`.
     *
     * @var AutoScalingGroupConfiguration
     */
    private $autoScalingGroup;

    /**
     * @param array{
     *   autoScalingGroup: AutoScalingGroupConfiguration|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->autoScalingGroup = isset($input['autoScalingGroup']) ? AutoScalingGroupConfiguration::create($input['autoScalingGroup']) : $this->throwException(new InvalidArgument('Missing required field "autoScalingGroup".'));
    }

    /**
     * @param array{
     *   autoScalingGroup: AutoScalingGroupConfiguration|array,
     * }|ParentInstanceConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAutoScalingGroup(): AutoScalingGroupConfiguration
    {
        return $this->autoScalingGroup;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
