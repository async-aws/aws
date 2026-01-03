<?php

namespace AsyncAws\MediaConvert\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\MediaConvert\Enum\BillingTagsSource;
use AsyncAws\MediaConvert\Enum\SimulateReservedQueue;
use AsyncAws\MediaConvert\Enum\StatusUpdateInterval;
use AsyncAws\MediaConvert\ValueObject\AccelerationSettings;
use AsyncAws\MediaConvert\ValueObject\HopDestination;
use AsyncAws\MediaConvert\ValueObject\JobSettings;

/**
 * Send your create job request with your job settings and IAM role. Optionally, include user metadata and the ARN for
 * the queue.
 */
final class CreateJobRequest extends Input
{
    /**
     * Optional. Accelerated transcoding can significantly speed up jobs with long, visually complex content. Outputs that
     * use this feature incur pro-tier pricing. For information about feature limitations, see the AWS Elemental
     * MediaConvert User Guide.
     *
     * @var AccelerationSettings|null
     */
    private $accelerationSettings;

    /**
     * Optionally choose a Billing tags source that AWS Billing and Cost Management will use to display tags for individual
     * output costs on any billing report that you set up. Leave blank to use the default value, Job.
     *
     * @var BillingTagsSource::*|null
     */
    private $billingTagsSource;

    /**
     * Prevent duplicate jobs from being created and ensure idempotency for your requests. A client request token can be any
     * string that includes up to 64 ASCII characters. If you reuse a client request token within one minute of a successful
     * request, the API returns the job details of the original request instead. For more information see
     * https://docs.aws.amazon.com/mediaconvert/latest/apireference/idempotency.html.
     *
     * @var string|null
     */
    private $clientRequestToken;

    /**
     * Optional. Use queue hopping to avoid overly long waits in the backlog of the queue that you submit your job to.
     * Specify an alternate queue and the maximum time that your job will wait in the initial queue before hopping. For more
     * information about this feature, see the AWS Elemental MediaConvert User Guide.
     *
     * @var HopDestination[]|null
     */
    private $hopDestinations;

    /**
     * Use Job engine versions to run jobs for your production workflow on one version, while you test and validate the
     * latest version. Job engine versions represent periodically grouped MediaConvert releases with new features, updates,
     * improvements, and fixes. Job engine versions are in a YYYY-MM-DD format. Note that the Job engine version feature is
     * not publicly available at this time. To request access, contact AWS support.
     *
     * @var string|null
     */
    private $jobEngineVersion;

    /**
     * Optional. When you create a job, you can either specify a job template or specify the transcoding settings
     * individually.
     *
     * @var string|null
     */
    private $jobTemplate;

    /**
     * Optional. Specify the relative priority for this job. In any given queue, the service begins processing the job with
     * the highest value first. When more than one job has the same priority, the service begins processing the job that you
     * submitted first. If you don't specify a priority, the service uses the default value 0.
     *
     * @var int|null
     */
    private $priority;

    /**
     * Optional. When you create a job, you can specify a queue to send it to. If you don't specify, the job will go to the
     * default queue. For more about queues, see the User Guide topic at
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/what-is.html.
     *
     * @var string|null
     */
    private $queue;

    /**
     * Required. The IAM role you use for creating this job. For details about permissions, see the User Guide topic at the
     * User Guide at https://docs.aws.amazon.com/mediaconvert/latest/ug/iam-role.html.
     *
     * @required
     *
     * @var string|null
     */
    private $role;

    /**
     * JobSettings contains all the transcode settings for a job.
     *
     * @required
     *
     * @var JobSettings|null
     */
    private $settings;

    /**
     * Optional. Enable this setting when you run a test job to estimate how many reserved transcoding slots (RTS) you need.
     * When this is enabled, MediaConvert runs your job from an on-demand queue with similar performance to what you will
     * see with one RTS in a reserved queue. This setting is disabled by default.
     *
     * @var SimulateReservedQueue::*|null
     */
    private $simulateReservedQueue;

    /**
     * Optional. Specify how often MediaConvert sends STATUS_UPDATE events to Amazon CloudWatch Events. Set the interval, in
     * seconds, between status updates. MediaConvert sends an update at this interval from the time the service begins
     * processing your job to the time it completes the transcode or encounters an error.
     *
     * @var StatusUpdateInterval::*|null
     */
    private $statusUpdateInterval;

    /**
     * Optional. The tags that you want to add to the resource. You can tag resources with a key-value pair or with only a
     * key. Use standard AWS tags on your job for automatic integration with AWS services and for custom integrations and
     * workflows.
     *
     * @var array<string, string>|null
     */
    private $tags;

    /**
     * Optional. User-defined metadata that you want to associate with an MediaConvert job. You specify metadata in
     * key/value pairs. Use only for existing integrations or workflows that rely on job metadata tags. Otherwise, we
     * recommend that you use standard AWS tags.
     *
     * @var array<string, string>|null
     */
    private $userMetadata;

    /**
     * @param array{
     *   AccelerationSettings?: AccelerationSettings|array|null,
     *   BillingTagsSource?: BillingTagsSource::*|null,
     *   ClientRequestToken?: string|null,
     *   HopDestinations?: array<HopDestination|array>|null,
     *   JobEngineVersion?: string|null,
     *   JobTemplate?: string|null,
     *   Priority?: int|null,
     *   Queue?: string|null,
     *   Role?: string,
     *   Settings?: JobSettings|array,
     *   SimulateReservedQueue?: SimulateReservedQueue::*|null,
     *   StatusUpdateInterval?: StatusUpdateInterval::*|null,
     *   Tags?: array<string, string>|null,
     *   UserMetadata?: array<string, string>|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->accelerationSettings = isset($input['AccelerationSettings']) ? AccelerationSettings::create($input['AccelerationSettings']) : null;
        $this->billingTagsSource = $input['BillingTagsSource'] ?? null;
        $this->clientRequestToken = $input['ClientRequestToken'] ?? null;
        $this->hopDestinations = isset($input['HopDestinations']) ? array_map([HopDestination::class, 'create'], $input['HopDestinations']) : null;
        $this->jobEngineVersion = $input['JobEngineVersion'] ?? null;
        $this->jobTemplate = $input['JobTemplate'] ?? null;
        $this->priority = $input['Priority'] ?? null;
        $this->queue = $input['Queue'] ?? null;
        $this->role = $input['Role'] ?? null;
        $this->settings = isset($input['Settings']) ? JobSettings::create($input['Settings']) : null;
        $this->simulateReservedQueue = $input['SimulateReservedQueue'] ?? null;
        $this->statusUpdateInterval = $input['StatusUpdateInterval'] ?? null;
        $this->tags = $input['Tags'] ?? null;
        $this->userMetadata = $input['UserMetadata'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   AccelerationSettings?: AccelerationSettings|array|null,
     *   BillingTagsSource?: BillingTagsSource::*|null,
     *   ClientRequestToken?: string|null,
     *   HopDestinations?: array<HopDestination|array>|null,
     *   JobEngineVersion?: string|null,
     *   JobTemplate?: string|null,
     *   Priority?: int|null,
     *   Queue?: string|null,
     *   Role?: string,
     *   Settings?: JobSettings|array,
     *   SimulateReservedQueue?: SimulateReservedQueue::*|null,
     *   StatusUpdateInterval?: StatusUpdateInterval::*|null,
     *   Tags?: array<string, string>|null,
     *   UserMetadata?: array<string, string>|null,
     *   '@region'?: string|null,
     * }|CreateJobRequest $input
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

    /**
     * @return HopDestination[]
     */
    public function getHopDestinations(): array
    {
        return $this->hopDestinations ?? [];
    }

    public function getJobEngineVersion(): ?string
    {
        return $this->jobEngineVersion;
    }

    public function getJobTemplate(): ?string
    {
        return $this->jobTemplate;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function getQueue(): ?string
    {
        return $this->queue;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function getSettings(): ?JobSettings
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
     * @return StatusUpdateInterval::*|null
     */
    public function getStatusUpdateInterval(): ?string
    {
        return $this->statusUpdateInterval;
    }

    /**
     * @return array<string, string>
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
    }

    /**
     * @return array<string, string>
     */
    public function getUserMetadata(): array
    {
        return $this->userMetadata ?? [];
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/2017-08-29/jobs';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setAccelerationSettings(?AccelerationSettings $value): self
    {
        $this->accelerationSettings = $value;

        return $this;
    }

    /**
     * @param BillingTagsSource::*|null $value
     */
    public function setBillingTagsSource(?string $value): self
    {
        $this->billingTagsSource = $value;

        return $this;
    }

    public function setClientRequestToken(?string $value): self
    {
        $this->clientRequestToken = $value;

        return $this;
    }

    /**
     * @param HopDestination[] $value
     */
    public function setHopDestinations(array $value): self
    {
        $this->hopDestinations = $value;

        return $this;
    }

    public function setJobEngineVersion(?string $value): self
    {
        $this->jobEngineVersion = $value;

        return $this;
    }

    public function setJobTemplate(?string $value): self
    {
        $this->jobTemplate = $value;

        return $this;
    }

    public function setPriority(?int $value): self
    {
        $this->priority = $value;

        return $this;
    }

    public function setQueue(?string $value): self
    {
        $this->queue = $value;

        return $this;
    }

    public function setRole(?string $value): self
    {
        $this->role = $value;

        return $this;
    }

    public function setSettings(?JobSettings $value): self
    {
        $this->settings = $value;

        return $this;
    }

    /**
     * @param SimulateReservedQueue::*|null $value
     */
    public function setSimulateReservedQueue(?string $value): self
    {
        $this->simulateReservedQueue = $value;

        return $this;
    }

    /**
     * @param StatusUpdateInterval::*|null $value
     */
    public function setStatusUpdateInterval(?string $value): self
    {
        $this->statusUpdateInterval = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setTags(array $value): self
    {
        $this->tags = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setUserMetadata(array $value): self
    {
        $this->userMetadata = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->accelerationSettings) {
            $payload['accelerationSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->billingTagsSource) {
            if (!BillingTagsSource::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "billingTagsSource" for "%s". The value "%s" is not a valid "BillingTagsSource".', __CLASS__, $v));
            }
            $payload['billingTagsSource'] = $v;
        }
        if (null === $v = $this->clientRequestToken) {
            $v = uuid_create(\UUID_TYPE_RANDOM);
        }
        $payload['clientRequestToken'] = $v;
        if (null !== $v = $this->hopDestinations) {
            $index = -1;
            $payload['hopDestinations'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['hopDestinations'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->jobEngineVersion) {
            $payload['jobEngineVersion'] = $v;
        }
        if (null !== $v = $this->jobTemplate) {
            $payload['jobTemplate'] = $v;
        }
        if (null !== $v = $this->priority) {
            $payload['priority'] = $v;
        }
        if (null !== $v = $this->queue) {
            $payload['queue'] = $v;
        }
        if (null === $v = $this->role) {
            throw new InvalidArgument(\sprintf('Missing parameter "Role" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['role'] = $v;
        if (null === $v = $this->settings) {
            throw new InvalidArgument(\sprintf('Missing parameter "Settings" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['settings'] = $v->requestBody();
        if (null !== $v = $this->simulateReservedQueue) {
            if (!SimulateReservedQueue::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "simulateReservedQueue" for "%s". The value "%s" is not a valid "SimulateReservedQueue".', __CLASS__, $v));
            }
            $payload['simulateReservedQueue'] = $v;
        }
        if (null !== $v = $this->statusUpdateInterval) {
            if (!StatusUpdateInterval::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "statusUpdateInterval" for "%s". The value "%s" is not a valid "StatusUpdateInterval".', __CLASS__, $v));
            }
            $payload['statusUpdateInterval'] = $v;
        }
        if (null !== $v = $this->tags) {
            if (empty($v)) {
                $payload['tags'] = new \stdClass();
            } else {
                $payload['tags'] = [];
                foreach ($v as $name => $mv) {
                    $payload['tags'][$name] = $mv;
                }
            }
        }
        if (null !== $v = $this->userMetadata) {
            if (empty($v)) {
                $payload['userMetadata'] = new \stdClass();
            } else {
                $payload['userMetadata'] = [];
                foreach ($v as $name => $mv) {
                    $payload['userMetadata'][$name] = $mv;
                }
            }
        }

        return $payload;
    }
}
