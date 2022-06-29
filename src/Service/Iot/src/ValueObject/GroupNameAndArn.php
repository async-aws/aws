<?php

namespace AsyncAws\Iot\ValueObject;

/**
 * The name and ARN of a group.
 */
final class GroupNameAndArn
{
    /**
     * The group name.
     */
    private $groupName;

    /**
     * The group ARN.
     */
    private $groupArn;

    /**
     * @param array{
     *   groupName?: null|string,
     *   groupArn?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->groupName = $input['groupName'] ?? null;
        $this->groupArn = $input['groupArn'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getGroupArn(): ?string
    {
        return $this->groupArn;
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }
}
