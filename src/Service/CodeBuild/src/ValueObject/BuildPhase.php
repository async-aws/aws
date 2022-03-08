<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\CodeBuild\Enum\BuildPhaseType;
use AsyncAws\CodeBuild\Enum\StatusType;

/**
 * Information about a stage for a build.
 */
final class BuildPhase
{
    /**
     * The name of the build phase. Valid values include:.
     */
    private $phaseType;

    /**
     * The current status of the build phase. Valid values include:.
     */
    private $phaseStatus;

    /**
     * When the build phase started, expressed in Unix time format.
     */
    private $startTime;

    /**
     * When the build phase ended, expressed in Unix time format.
     */
    private $endTime;

    /**
     * How long, in seconds, between the starting and ending times of the build's phase.
     */
    private $durationInSeconds;

    /**
     * Additional information about a build phase, especially to help troubleshoot a failed build.
     */
    private $contexts;

    /**
     * @param array{
     *   phaseType?: null|BuildPhaseType::*,
     *   phaseStatus?: null|StatusType::*,
     *   startTime?: null|\DateTimeImmutable,
     *   endTime?: null|\DateTimeImmutable,
     *   durationInSeconds?: null|string,
     *   contexts?: null|PhaseContext[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->phaseType = $input['phaseType'] ?? null;
        $this->phaseStatus = $input['phaseStatus'] ?? null;
        $this->startTime = $input['startTime'] ?? null;
        $this->endTime = $input['endTime'] ?? null;
        $this->durationInSeconds = $input['durationInSeconds'] ?? null;
        $this->contexts = isset($input['contexts']) ? array_map([PhaseContext::class, 'create'], $input['contexts']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return PhaseContext[]
     */
    public function getContexts(): array
    {
        return $this->contexts ?? [];
    }

    public function getDurationInSeconds(): ?string
    {
        return $this->durationInSeconds;
    }

    public function getEndTime(): ?\DateTimeImmutable
    {
        return $this->endTime;
    }

    /**
     * @return StatusType::*|null
     */
    public function getPhaseStatus(): ?string
    {
        return $this->phaseStatus;
    }

    /**
     * @return BuildPhaseType::*|null
     */
    public function getPhaseType(): ?string
    {
        return $this->phaseType;
    }

    public function getStartTime(): ?\DateTimeImmutable
    {
        return $this->startTime;
    }
}
