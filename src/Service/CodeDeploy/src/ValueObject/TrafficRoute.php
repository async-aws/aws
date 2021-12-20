<?php

namespace AsyncAws\CodeDeploy\ValueObject;

/**
 * The path used by a load balancer to route production traffic when an Amazon ECS deployment is complete.
 */
final class TrafficRoute
{
    /**
     * The Amazon Resource Name (ARN) of one listener. The listener identifies the route between a target group and a load
     * balancer. This is an array of strings with a maximum size of one.
     */
    private $listenerArns;

    /**
     * @param array{
     *   listenerArns?: null|string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->listenerArns = $input['listenerArns'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getListenerArns(): array
    {
        return $this->listenerArns ?? [];
    }
}
