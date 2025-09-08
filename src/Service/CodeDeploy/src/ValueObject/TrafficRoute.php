<?php

namespace AsyncAws\CodeDeploy\ValueObject;

/**
 * Information about a listener. The listener contains the path used to route traffic that is received from the load
 * balancer to a target group.
 */
final class TrafficRoute
{
    /**
     * The Amazon Resource Name (ARN) of one listener. The listener identifies the route between a target group and a load
     * balancer. This is an array of strings with a maximum size of one.
     *
     * @var string[]|null
     */
    private $listenerArns;

    /**
     * @param array{
     *   listenerArns?: string[]|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->listenerArns = $input['listenerArns'] ?? null;
    }

    /**
     * @param array{
     *   listenerArns?: string[]|null,
     * }|TrafficRoute $input
     */
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
