<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\MediaConvert\Enum\AccelerationStatus;
use AsyncAws\MediaConvert\Enum\BillingTagsSource;
use AsyncAws\MediaConvert\Enum\JobPhase;
use AsyncAws\MediaConvert\Enum\JobStatus;
use AsyncAws\MediaConvert\Enum\SimulateReservedQueue;
use AsyncAws\MediaConvert\Enum\StatusUpdateInterval;

/**
 * Each job converts an input file into an output file or files. For more information, see the User Guide at
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/what-is.html.
 */
final class Job
{
    /**
     * Accelerated transcoding can significantly speed up jobs with long, visually complex content.
     */
    private $accelerationSettings;

    /**
     * Describes whether the current job is running with accelerated transcoding. For jobs that have Acceleration
     * (AccelerationMode) set to DISABLED, AccelerationStatus is always NOT_APPLICABLE. For jobs that have Acceleration
     * (AccelerationMode) set to ENABLED or PREFERRED, AccelerationStatus is one of the other states. AccelerationStatus is
     * IN_PROGRESS initially, while the service determines whether the input files and job settings are compatible with
     * accelerated transcoding. If they are, AcclerationStatus is ACCELERATED. If your input files and job settings aren't
     * compatible with accelerated transcoding, the service either fails your job or runs it without accelerated
     * transcoding, depending on how you set Acceleration (AccelerationMode). When the service runs your job without
     * accelerated transcoding, AccelerationStatus is NOT_ACCELERATED.
     */
    private $accelerationStatus;

    /**
     * An identifier for this resource that is unique within all of AWS.
     */
    private $arn;

    /**
     * The tag type that AWS Billing and Cost Management will use to sort your AWS Elemental MediaConvert costs on any
     * billing report that you set up.
     */
    private $billingTagsSource;

    /**
     * Prevent duplicate jobs from being created and ensure idempotency for your requests. A client request token can be any
     * string that includes up to 64 ASCII characters. If you reuse a client request token within one minute of a successful
     * request, the API returns the job details of the original request instead. For more information see
     * https://docs.aws.amazon.com/mediaconvert/latest/apireference/idempotency.html.
     */
    private $clientRequestToken;

    /**
     * The time, in Unix epoch format in seconds, when the job got created.
     */
    private $createdAt;

    /**
     * A job's phase can be PROBING, TRANSCODING OR UPLOADING.
     */
    private $currentPhase;

    /**
     * Error code for the job.
     */
    private $errorCode;

    /**
     * Error message of Job.
     */
    private $errorMessage;

    /**
     * Optional list of hop destinations.
     */
    private $hopDestinations;

    /**
     * A portion of the job's ARN, unique within your AWS Elemental MediaConvert resources.
     */
    private $id;

    /**
     * An estimate of how far your job has progressed. This estimate is shown as a percentage of the total time from when
     * your job leaves its queue to when your output files appear in your output Amazon S3 bucket. AWS Elemental
     * MediaConvert provides jobPercentComplete in CloudWatch STATUS_UPDATE events and in the response to GetJob and
     * ListJobs requests. The jobPercentComplete estimate is reliable for the following input containers: Quicktime,
     * Transport Stream, MP4, and MXF. For some jobs, the service can't provide information about job progress. In those
     * cases, jobPercentComplete returns a null value.
     */
    private $jobPercentComplete;

    /**
     * The job template that the job is created from, if it is created from a job template.
     */
    private $jobTemplate;

    /**
     * Provides messages from the service about jobs that you have already successfully submitted.
     */
    private $messages;

    /**
     * List of output group details.
     */
    private $outputGroupDetails;

    /**
     * Relative priority on the job.
     */
    private $priority;

    /**
     * When you create a job, you can specify a queue to send it to. If you don't specify, the job will go to the default
     * queue. For more about queues, see the User Guide topic at
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/what-is.html.
     */
    private $queue;

    /**
     * The job's queue hopping history.
     */
    private $queueTransitions;

    /**
     * The number of times that the service automatically attempted to process your job after encountering an error.
     */
    private $retryCount;

    /**
     * The IAM role you use for creating this job. For details about permissions, see the User Guide topic at the User Guide
     * at https://docs.aws.amazon.com/mediaconvert/latest/ug/iam-role.html.
     */
    private $role;

    /**
     * JobSettings contains all the transcode settings for a job.
     */
    private $settings;

    /**
     * Enable this setting when you run a test job to estimate how many reserved transcoding slots (RTS) you need. When this
     * is enabled, MediaConvert runs your job from an on-demand queue with similar performance to what you will see with one
     * RTS in a reserved queue. This setting is disabled by default.
     */
    private $simulateReservedQueue;

    /**
     * A job's status can be SUBMITTED, PROGRESSING, COMPLETE, CANCELED, or ERROR.
     */
    private $status;

    /**
     * Specify how often MediaConvert sends STATUS_UPDATE events to Amazon CloudWatch Events. Set the interval, in seconds,
     * between status updates. MediaConvert sends an update at this interval from the time the service begins processing
     * your job to the time it completes the transcode or encounters an error.
     */
    private $statusUpdateInterval;

    /**
     * Information about when jobs are submitted, started, and finished is specified in Unix epoch format in seconds.
     */
    private $timing;

    /**
     * User-defined metadata that you want to associate with an MediaConvert job. You specify metadata in key/value pairs.
     */
    private $userMetadata;

    /**
     * Contains any warning messages for the job. Use to help identify potential issues with your input, output, or job. For
     * more information, see https://docs.aws.amazon.com/mediaconvert/latest/ug/warning_codes.html.
     */
    private $warnings;

    /**
     * @param array{
     *   AccelerationSettings?: null|AccelerationSettings|array,
     *   AccelerationStatus?: null|AccelerationStatus::*,
     *   Arn?: null|string,
     *   BillingTagsSource?: null|BillingTagsSource::*,
     *   ClientRequestToken?: null|string,
     *   CreatedAt?: null|\DateTimeImmutable,
     *   CurrentPhase?: null|JobPhase::*,
     *   ErrorCode?: null|int,
     *   ErrorMessage?: null|string,
     *   HopDestinations?: null|array<HopDestination|array>,
     *   Id?: null|string,
     *   JobPercentComplete?: null|int,
     *   JobTemplate?: null|string,
     *   Messages?: null|JobMessages|array,
     *   OutputGroupDetails?: null|array<OutputGroupDetail|array>,
     *   Priority?: null|int,
     *   Queue?: null|string,
     *   QueueTransitions?: null|array<QueueTransition|array>,
     *   RetryCount?: null|int,
     *   Role: string,
     *   Settings: JobSettings|array,
     *   SimulateReservedQueue?: null|SimulateReservedQueue::*,
     *   Status?: null|JobStatus::*,
     *   StatusUpdateInterval?: null|StatusUpdateInterval::*,
     *   Timing?: null|Timing|array,
     *   UserMetadata?: null|array<string, string>,
     *   Warnings?: null|array<WarningGroup|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->accelerationSettings = isset($input['AccelerationSettings']) ? AccelerationSettings::create($input['AccelerationSettings']) : null;
        $this->accelerationStatus = $input['AccelerationStatus'] ?? null;
        $this->arn = $input['Arn'] ?? null;
        $this->billingTagsSource = $input['BillingTagsSource'] ?? null;
        $this->clientRequestToken = $input['ClientRequestToken'] ?? null;
        $this->createdAt = $input['CreatedAt'] ?? null;
        $this->currentPhase = $input['CurrentPhase'] ?? null;
        $this->errorCode = $input['ErrorCode'] ?? null;
        $this->errorMessage = $input['ErrorMessage'] ?? null;
        $this->hopDestinations = isset($input['HopDestinations']) ? array_map([HopDestination::class, 'create'], $input['HopDestinations']) : null;
        $this->id = $input['Id'] ?? null;
        $this->jobPercentComplete = $input['JobPercentComplete'] ?? null;
        $this->jobTemplate = $input['JobTemplate'] ?? null;
        $this->messages = isset($input['Messages']) ? JobMessages::create($input['Messages']) : null;
        $this->outputGroupDetails = isset($input['OutputGroupDetails']) ? array_map([OutputGroupDetail::class, 'create'], $input['OutputGroupDetails']) : null;
        $this->priority = $input['Priority'] ?? null;
        $this->queue = $input['Queue'] ?? null;
        $this->queueTransitions = isset($input['QueueTransitions']) ? array_map([QueueTransition::class, 'create'], $input['QueueTransitions']) : null;
        $this->retryCount = $input['RetryCount'] ?? null;
        $this->role = $input['Role'] ?? null;
        $this->settings = isset($input['Settings']) ? JobSettings::create($input['Settings']) : null;
        $this->simulateReservedQueue = $input['SimulateReservedQueue'] ?? null;
        $this->status = $input['Status'] ?? null;
        $this->statusUpdateInterval = $input['StatusUpdateInterval'] ?? null;
        $this->timing = isset($input['Timing']) ? Timing::create($input['Timing']) : null;
        $this->userMetadata = $input['UserMetadata'] ?? null;
        $this->warnings = isset($input['Warnings']) ? array_map([WarningGroup::class, 'create'], $input['Warnings']) : null;
    }

    /**
     * @param array{
     *   AccelerationSettings?: null|AccelerationSettings|array,
     *   AccelerationStatus?: null|AccelerationStatus::*,
     *   Arn?: null|string,
     *   BillingTagsSource?: null|BillingTagsSource::*,
     *   ClientRequestToken?: null|string,
     *   CreatedAt?: null|\DateTimeImmutable,
     *   CurrentPhase?: null|JobPhase::*,
     *   ErrorCode?: null|int,
     *   ErrorMessage?: null|string,
     *   HopDestinations?: null|array<HopDestination|array>,
     *   Id?: null|string,
     *   JobPercentComplete?: null|int,
     *   JobTemplate?: null|string,
     *   Messages?: null|JobMessages|array,
     *   OutputGroupDetails?: null|array<OutputGroupDetail|array>,
     *   Priority?: null|int,
     *   Queue?: null|string,
     *   QueueTransitions?: null|array<QueueTransition|array>,
     *   RetryCount?: null|int,
     *   Role: string,
     *   Settings: JobSettings|array,
     *   SimulateReservedQueue?: null|SimulateReservedQueue::*,
     *   Status?: null|JobStatus::*,
     *   StatusUpdateInterval?: null|StatusUpdateInterval::*,
     *   Timing?: null|Timing|array,
     *   UserMetadata?: null|array<string, string>,
     *   Warnings?: null|array<WarningGroup|array>,
     * }|Job $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccelerationSettings(): ?AccelerationSettings
    {
        return $this->accelerationSettings;
    }

    /**
     * @return AccelerationStatus::*|null
     */
    public function getAccelerationStatus(): ?string
    {
        return $this->accelerationStatus;
    }

    public function getArn(): ?string
    {
        return $this->arn;
    }

    /**
     * @return BillingTagsSource::*|null
     */
    public function getBillingTagsSource(): ?string
    {
        return $this->billingTagsSource;
    }

    public function getClientRequestToken(): ?string
    {
        return $this->clientRequestToken;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return JobPhase::*|null
     */
    public function getCurrentPhase(): ?string
    {
        return $this->currentPhase;
    }

    public function getErrorCode(): ?int
    {
        return $this->errorCode;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * @return HopDestination[]
     */
    public function getHopDestinations(): array
    {
        return $this->hopDestinations ?? [];
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getJobPercentComplete(): ?int
    {
        return $this->jobPercentComplete;
    }

    public function getJobTemplate(): ?string
    {
        return $this->jobTemplate;
    }

    public function getMessages(): ?JobMessages
    {
        return $this->messages;
    }

    /**
     * @return OutputGroupDetail[]
     */
    public function getOutputGroupDetails(): array
    {
        return $this->outputGroupDetails ?? [];
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function getQueue(): ?string
    {
        return $this->queue;
    }

    /**
     * @return QueueTransition[]
     */
    public function getQueueTransitions(): array
    {
        return $this->queueTransitions ?? [];
    }

    public function getRetryCount(): ?int
    {
        return $this->retryCount;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getSettings(): JobSettings
    {
        return $this->settings;
    }

    /**
     * @return SimulateReservedQueue::*|null
     */
    public function getSimulateReservedQueue(): ?string
    {
        return $this->simulateReservedQueue;
    }

    /**
     * @return JobStatus::*|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return StatusUpdateInterval::*|null
     */
    public function getStatusUpdateInterval(): ?string
    {
        return $this->statusUpdateInterval;
    }

    public function getTiming(): ?Timing
    {
        return $this->timing;
    }

    /**
     * @return array<string, string>
     */
    public function getUserMetadata(): array
    {
        return $this->userMetadata ?? [];
    }

    /**
     * @return WarningGroup[]
     */
    public function getWarnings(): array
    {
        return $this->warnings ?? [];
    }
}
