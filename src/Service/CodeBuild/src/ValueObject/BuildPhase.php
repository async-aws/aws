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
     * The name of the build phase. Valid values include:
     *
     * - `BUILD`:
     *
     *   Core build activities typically occur in this build phase.
     * - `COMPLETED`:
     *
     *   The build has been completed.
     * - `DOWNLOAD_SOURCE`:
     *
     *   Source code is being downloaded in this build phase.
     * - `FINALIZING`:
     *
     *   The build process is completing in this build phase.
     * - `INSTALL`:
     *
     *   Installation activities typically occur in this build phase.
     * - `POST_BUILD`:
     *
     *   Post-build activities typically occur in this build phase.
     * - `PRE_BUILD`:
     *
     *   Pre-build activities typically occur in this build phase.
     * - `PROVISIONING`:
     *
     *   The build environment is being set up.
     * - `QUEUED`:
     *
     *   The build has been submitted and is queued behind other submitted builds.
     * - `SUBMITTED`:
     *
     *   The build has been submitted.
     * - `UPLOAD_ARTIFACTS`:
     *
     *   Build output artifacts are being uploaded to the output location.
     *
     * @var BuildPhaseType::*|null
     */
    private $phaseType;

    /**
     * The current status of the build phase. Valid values include:
     *
     * - `FAILED`:
     *
     *   The build phase failed.
     * - `FAULT`:
     *
     *   The build phase faulted.
     * - `IN_PROGRESS`:
     *
     *   The build phase is still in progress.
     * - `STOPPED`:
     *
     *   The build phase stopped.
     * - `SUCCEEDED`:
     *
     *   The build phase succeeded.
     * - `TIMED_OUT`:
     *
     *   The build phase timed out.
     *
     * @var StatusType::*|null
     */
    private $phaseStatus;

    /**
     * When the build phase started, expressed in Unix time format.
     *
     * @var \DateTimeImmutable|null
     */
    private $startTime;

    /**
     * When the build phase ended, expressed in Unix time format.
     *
     * @var \DateTimeImmutable|null
     */
    private $endTime;

    /**
     * How long, in seconds, between the starting and ending times of the build's phase.
     *
     * @var int|null
     */
    private $durationInSeconds;

    /**
     * Additional information about a build phase, especially to help troubleshoot a failed build.
     *
     * @var PhaseContext[]|null
     */
    private $contexts;

    /**
     * @param array{
     *   phaseType?: BuildPhaseType::*|null,
     *   phaseStatus?: StatusType::*|null,
     *   startTime?: \DateTimeImmutable|null,
     *   endTime?: \DateTimeImmutable|null,
     *   durationInSeconds?: int|null,
     *   contexts?: array<PhaseContext|array>|null,
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

    /**
     * @param array{
     *   phaseType?: BuildPhaseType::*|null,
     *   phaseStatus?: StatusType::*|null,
     *   startTime?: \DateTimeImmutable|null,
     *   endTime?: \DateTimeImmutable|null,
     *   durationInSeconds?: int|null,
     *   contexts?: array<PhaseContext|array>|null,
     * }|BuildPhase $input
     */
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

    public function getDurationInSeconds(): ?int
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
