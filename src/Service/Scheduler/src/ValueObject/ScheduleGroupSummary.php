<?php

namespace AsyncAws\Scheduler\ValueObject;

use AsyncAws\Scheduler\Enum\ScheduleGroupState;

/**
 * The details of a schedule group.
 */
final class ScheduleGroupSummary
{
    /**
     * The Amazon Resource Name (ARN) of the schedule group.
     *
     * @var string|null
     */
    private $arn;

    /**
     * The time at which the schedule group was created.
     *
     * @var \DateTimeImmutable|null
     */
    private $creationDate;

    /**
     * The time at which the schedule group was last modified.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastModificationDate;

    /**
     * The name of the schedule group.
     *
     * @var string|null
     */
    private $name;

    /**
     * Specifies the state of the schedule group.
     *
     * @var ScheduleGroupState::*|string|null
     */
    private $state;

    /**
     * @param array{
     *   Arn?: null|string,
     *   CreationDate?: null|\DateTimeImmutable,
     *   LastModificationDate?: null|\DateTimeImmutable,
     *   Name?: null|string,
     *   State?: null|ScheduleGroupState::*|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arn = $input['Arn'] ?? null;
        $this->creationDate = $input['CreationDate'] ?? null;
        $this->lastModificationDate = $input['LastModificationDate'] ?? null;
        $this->name = $input['Name'] ?? null;
        $this->state = $input['State'] ?? null;
    }

    /**
     * @param array{
     *   Arn?: null|string,
     *   CreationDate?: null|\DateTimeImmutable,
     *   LastModificationDate?: null|\DateTimeImmutable,
     *   Name?: null|string,
     *   State?: null|ScheduleGroupState::*|string,
     * }|ScheduleGroupSummary $input
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

    public function getLastModificationDate(): ?\DateTimeImmutable
    {
        return $this->lastModificationDate;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return ScheduleGroupState::*|string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }
}
