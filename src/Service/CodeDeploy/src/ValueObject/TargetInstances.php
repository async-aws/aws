<?php

namespace AsyncAws\CodeDeploy\ValueObject;

/**
 * Information about the instances to be used in the replacement environment in a blue/green deployment.
 */
final class TargetInstances
{
    /**
     * The tag filter key, type, and value used to identify Amazon EC2 instances in a replacement environment for a
     * blue/green deployment. Cannot be used in the same call as `ec2TagSet`.
     *
     * @var EC2TagFilter[]|null
     */
    private $tagFilters;

    /**
     * The names of one or more Auto Scaling groups to identify a replacement environment for a blue/green deployment.
     *
     * @var string[]|null
     */
    private $autoScalingGroups;

    /**
     * Information about the groups of Amazon EC2 instance tags that an instance must be identified by in order for it to be
     * included in the replacement environment for a blue/green deployment. Cannot be used in the same call as `tagFilters`.
     *
     * @var EC2TagSet|null
     */
    private $ec2TagSet;

    /**
     * @param array{
     *   tagFilters?: array<EC2TagFilter|array>|null,
     *   autoScalingGroups?: string[]|null,
     *   ec2TagSet?: EC2TagSet|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->tagFilters = isset($input['tagFilters']) ? array_map([EC2TagFilter::class, 'create'], $input['tagFilters']) : null;
        $this->autoScalingGroups = $input['autoScalingGroups'] ?? null;
        $this->ec2TagSet = isset($input['ec2TagSet']) ? EC2TagSet::create($input['ec2TagSet']) : null;
    }

    /**
     * @param array{
     *   tagFilters?: array<EC2TagFilter|array>|null,
     *   autoScalingGroups?: string[]|null,
     *   ec2TagSet?: EC2TagSet|array|null,
     * }|TargetInstances $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getAutoScalingGroups(): array
    {
        return $this->autoScalingGroups ?? [];
    }

    public function getEc2TagSet(): ?EC2TagSet
    {
        return $this->ec2TagSet;
    }

    /**
     * @return EC2TagFilter[]
     */
    public function getTagFilters(): array
    {
        return $this->tagFilters ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->tagFilters) {
            $index = -1;
            $payload['tagFilters'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['tagFilters'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->autoScalingGroups) {
            $index = -1;
            $payload['autoScalingGroups'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['autoScalingGroups'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->ec2TagSet) {
            $payload['ec2TagSet'] = $v->requestBody();
        }

        return $payload;
    }
}
