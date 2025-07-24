<?php

namespace AsyncAws\Scheduler\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Scheduler\Enum\ScheduleGroupState;

class GetScheduleGroupOutput extends Result
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

    public function getArn(): ?string
    {
        $this->initialize();

        return $this->arn;
    }

    public function getCreationDate(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->creationDate;
    }

    public function getLastModificationDate(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->lastModificationDate;
    }

    public function getName(): ?string
    {
        $this->initialize();

        return $this->name;
    }

    /**
     * @return ScheduleGroupState::*|string|null
     */
    public function getState(): ?string
    {
        $this->initialize();

        return $this->state;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->arn = isset($data['Arn']) ? (string) $data['Arn'] : null;
        $this->creationDate = isset($data['CreationDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['CreationDate']))) ? $d : null;
        $this->lastModificationDate = isset($data['LastModificationDate']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['LastModificationDate']))) ? $d : null;
        $this->name = isset($data['Name']) ? (string) $data['Name'] : null;
        $this->state = isset($data['State']) ? (string) $data['State'] : null;
    }
}
