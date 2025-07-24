<?php

namespace AsyncAws\Scheduler\ValueObject;

use AsyncAws\Scheduler\Enum\ScheduleState;

/**
 * The details of a schedule.
 */
final class ScheduleSummary
{
    /**
     * The Amazon Resource Name (ARN) of the schedule.
     *
     * @var string|null
     */
    private $arn;

    /**
     * The time at which the schedule was created.
     *
     * @var \DateTimeImmutable|null
     */
    private $creationDate;

    /**
     * The name of the schedule group associated with this schedule.
     *
     * @var string|null
     */
    private $groupName;

    /**
     * The time at which the schedule was last modified.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastModificationDate;

    /**
     * The name of the schedule.
     *
     * @var string|null
     */
    private $name;

    /**
     * Specifies whether the schedule is enabled or disabled.
     *
     * @var ScheduleState::*|string|null
     */
    private $state;

    /**
     * The schedule's target details.
     *
     * @var TargetSummary|null
     */
    private $target;

    /**
     * @param array{
     *   Arn?: null|string,
     *   CreationDate?: null|\DateTimeImmutable,
     *   GroupName?: null|string,
     *   LastModificationDate?: null|\DateTimeImmutable,
     *   Name?: null|string,
     *   State?: null|ScheduleState::*|string,
     *   Target?: null|TargetSummary|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arn = $input['Arn'] ?? null;
        $this->creationDate = $input['CreationDate'] ?? null;
        $this->groupName = $input['GroupName'] ?? null;
        $this->lastModificationDate = $input['LastModificationDate'] ?? null;
        $this->name = $input['Name'] ?? null;
        $this->state = $input['State'] ?? null;
        $this->target = isset($input['Target']) ? TargetSummary::create($input['Target']) : null;
    }

    /**
     * @param array{
     *   Arn?: null|string,
     *   CreationDate?: null|\DateTimeImmutable,
     *   GroupName?: null|string,
     *   LastModificationDate?: null|\DateTimeImmutable,
     *   Name?: null|string,
     *   State?: null|ScheduleState::*|string,
     *   Target?: null|TargetSummary|array,
     * }|ScheduleSummary $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArn(): ?string
    {
        return $this->arn;
    }

    public function getCreationDate(): ?\DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function getLastModificationDate(): ?\DateTimeImmutable
    {
        return $this->lastModificationDate;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return ScheduleState::*|string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    public function getTarget(): ?TargetSummary
    {
        return $this->target;
    }
}
