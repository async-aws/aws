<?php

namespace AsyncAws\Iot\ValueObject;

/**
 * The name and ARN of a group.
 */
final class GroupNameAndArn
{
    /**
     * The group name.
     *
     * @var string|null
     */
    private $groupName;

    /**
     * The group ARN.
     *
     * @var string|null
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

    /**
     * @param array{
     *   groupName?: null|string,
     *   groupArn?: null|string,
     * }|GroupNameAndArn $input
     */
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
