<?php

namespace AsyncAws\CodeDeploy\ValueObject;

/**
 * Information about the groups of EC2 instance tags that an instance must be identified by in order for it to be
 * included in the replacement environment for a blue/green deployment. Cannot be used in the same call as `tagFilters`.
 */
final class EC2TagSet
{
    /**
     * A list that contains other lists of EC2 instance tag groups. For an instance to be included in the deployment group,
     * it must be identified by all of the tag groups in the list.
     */
    private $ec2TagSetList;

    /**
     * @param array{
     *   ec2TagSetList?: null|array[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->ec2TagSetList = $input['ec2TagSetList'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return EC2TagFilter[][]
     */
    public function getEc2TagSetList(): array
    {
        return $this->ec2TagSetList ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->ec2TagSetList) {
            $index = -1;
            $payload['ec2TagSetList'] = [];
            foreach ($v as $listValue) {
                ++$index;

                $index1 = -1;
                $payload['ec2TagSetList'][$index] = [];
                foreach ($listValue as $listValue1) {
                    ++$index1;
                    $payload['ec2TagSetList'][$index][$index1] = $listValue1->requestBody();
                }
            }
        }

        return $payload;
    }
}
